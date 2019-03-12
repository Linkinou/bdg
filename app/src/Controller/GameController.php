<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Location;
use App\Entity\Persona;
use App\Entity\RolePlay;
use App\Entity\User;
use App\Exception\ForbiddenAccessException;
use App\FormType\GmSortingFormType;
use App\FormType\GameCreationFormType;
use App\FormType\JoiningGameFormType;
use App\FormType\RolePlayFormType;
use App\Model\GameModel;
use App\Model\JoiningGameModel;
use App\Model\RolePlayModel;
use App\Repository\GameRepository;
use App\Repository\LocationRepository;
use App\Repository\RolePlayRepository;
use App\Service\GameService;
use App\Service\RolePlayService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/rpg/{locationSlug}/{gameSlug}", name="rpg_game_")
 * @ParamConverter("location", options={"mapping": {"locationSlug": "slug"}})
 * @ParamConverter("game", options={"mapping": {"gameSlug": "slug"}})
 */
class GameController extends AbstractController
{
    /**
     * @Route("/", name="view")
     */
    public function viewGame(
        Request $request,
        TranslatorInterface $translator,
        RolePlayRepository $rolePlayRepository,
        Location $location,
        Game $game
    ){
        /** @var User $user */
        $user = $this->getUser();

        // If draft and not owner
        if ($game->getState() === 'draft' && !$game->isGameMaster($user)) {
            throw $this->createNotFoundException($translator->trans('common.flash.not_found'));
        }

        $page = $request->query->get('page', 1);
        $rolePlays = $rolePlayRepository->findLatest($game, $page);

        $joiningForm = null;
        $gmSortingForm = null;
        if (null !== $user) {
            $joiningGame = new JoiningGameModel();
            $joiningForm = $this->getJoiningGameForm($user, $game, $joiningGame);

            if ($game->isGameMaster($user)) {
                $gmSortingForm = $this->createForm(GmSortingFormType::class, null, [
                    'personas' => $game->getPendingPersonas()
                ]);
            }
        }

        return $this->render('rpg/game.html.twig', [
            'game' => $game,
            'joiningForm' => $joiningForm ? $joiningForm->createView() : null,
            'gmSortingForm' => $gmSortingForm ? $gmSortingForm->createView() : null,
            'rolePlays' => $rolePlays,
        ]);
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function editGame(
        Request $request,
        TranslatorInterface $translator,
        GameService $gameService,
        EntityManagerInterface $em,
        Location $location,
        Game $game
    ){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $gameModel = GameModel::createFromGame($game);

        $form = $this->createForm(GameCreationFormType::class, $gameModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game = $gameService->edit($game, $gameModel);
            $em->flush();

            $this->addFlash('success', $translator->trans('game.flash.game_edited'));

            return $this->redirectToRoute('rpg_game_view', [
                'locationSlug' => $game->getLocation()->getSlug(),
                'gameSlug' => $game->getSlug()
            ]);
        }

        return $this->render('rpg/new_game.html.twig', [
            'form' => $form->createView(),
            'location' => $location
        ]);
    }

    /**
     * @Route("/start", name="start")
     */
    public function startGame(
        TranslatorInterface $translator,
        GameService $gameService,
        Location $location,
        Game $game
    ){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($gameService->applyTransition($game, 'start')) {
            $this->addFlash('success', $translator->trans('game.flash.game_started'));
        }

        return $this->redirectToRoute('rpg_game_view', [
            'locationSlug' => $location->getSlug(),
            'gameSlug' => $game->getSlug()
        ]);
    }

    /**
     * @Route("/publish", name="publish")
     */
    public function publishGame(
        TranslatorInterface $translator,
        GameService $gameService,
        Location $location,
        Game $game
    ){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($gameService->applyTransition($game, 'publish')) {
            $this->addFlash('success', $translator->trans('game.flash.game_published'));
        }

        return $this->redirectToRoute('rpg_game_view', [
            'locationSlug' => $location->getSlug(),
            'gameSlug' => $game->getSlug()
        ]);
    }

    /**
     * @Route("/leave", name="leave")
     */
    public function leaveGame(TranslatorInterface $translator, GameService $gameService, Location $location, Game $game)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var User $user */
        $user = $this->getUser();

        if ($gameService->leave($game, $user)) {
            $this->addFlash('success', $translator->trans('game.flash.left'));
        }

        return $this->redirectToRoute('rpg_game_view', [
            'locationSlug' => $location->getSlug(),
            'gameSlug' => $game->getSlug()
        ]);
    }

    /**
     * @Route("/join", name="join", methods={"POST"})
     */
    public function joinGame(
        Request $request,
        TranslatorInterface $translator,
        GameService $gameService,
        Location $location,
        Game $game
    ){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var User $user */
        $user = $this->getUser();

        $joiningGame = new JoiningGameModel();
        $form = $this->getJoiningGameForm($user, $game, $joiningGame);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($gameService->join($game, $user, $joiningGame->getPersona())) {
                $this->addFlash('success', $translator->trans('game.flash.joined'));
            }
        }

        return $this->redirectToRoute('rpg_game_view', [
            'locationSlug' => $location->getSlug(),
            'gameSlug' => $game->getSlug()
        ]);
    }

    /**
     * @Route("/accept/{personaId}", name="accept_persona", methods={"GET"})
     * @ParamConverter("persona", options={"mapping": {"personaId": "id"}})
     */
    public function acceptPersona(
        GameService $gameService,
        TranslatorInterface $translator,
        Location $location,
        Game $game,
        Persona $persona
    ){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var User $user */
        $user = $this->getUser();

        if ($gameService->acceptPersona($game, $user, $persona)) {
            $this->addFlash('success', $translator->trans('game.flash.persona_accepted', ['%name%' => $persona->getName()]));
        }

        return $this->redirectToRoute('rpg_game_view', [
            'locationSlug' => $location->getSlug(),
            'gameSlug' => $game->getSlug()
        ]);
    }

    /**
     * @Route("/reject/{personaId}", name="reject_persona", methods={"GET"})
     * @ParamConverter("persona", options={"mapping": {"personaId": "slug"}})
     */
    public function rejectPersona(
        GameService $gameService,
        TranslatorInterface $translator,
        Location $location,
        Game $game,
        Persona $persona
    ){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var User $user */
        $user = $this->getUser();

        if ($gameService->refusePersona($game, $user, $persona)) {
            $this->addFlash('success', $translator->trans('game.flash.persona_rejected', ['%name%' => $persona->getName()]));
        }

        return $this->redirectToRoute('rpg_game_view', [
            'locationSlug' => $location->getSlug(),
            'gameSlug' => $game->getSlug()
        ]);
    }

    /**
     * @Route("/post", name="post", methods={"GET", "POST"})
     */
    public function postRolePlay(
        Request $request,
        EntityManagerInterface $em,
        TranslatorInterface $translator,
        RolePlayService $rolePlayService,
        Location $location,
        Game $game
    ){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $rolePlayModel = new RolePlayModel();
        $form = $this->createForm(RolePlayFormType::class, $rolePlayModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rolePlay = $rolePlayService->createRolePlay($rolePlayModel, $game, $this->getUser());

            $em->persist($rolePlay);
            $em->flush();

            $this->addFlash('success', $translator->trans('role_play.flash.rp_created'));

            return $this->redirectToRoute('rpg_game_view', [
                'locationSlug' => $location->getSlug(),
                'gameSlug' => $game->getSlug()
            ]);
        }

        return $this->render('rpg/role_play/new.html.twig', [
            'submitValue' => $translator->trans('role_play.actions.create'),
            'game' => $game,
            'location' => $location,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{rolePlayId}/edit", name="roleplay_edit", methods={"GET", "POST"})
     */
    public function editRolePlay(
        Request $request,
        EntityManagerInterface $em,
        TranslatorInterface $translator,
        RolePlayService $rolePlayService,
        Location $location,
        Game $game,
        RolePlay $rolePlay
    ){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($rolePlay->getPersona()->getUser() !== $this->getUser()) {
            throw new ForbiddenAccessException('Player tried editing other user RP');
        }

        $rolePlayModel = RolePlayModel::createFromRolePlay($rolePlay);
        $form = $this->createForm(RolePlayFormType::class, $rolePlayModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rolePlay = $rolePlayService->editRolePlay($rolePlayModel, $rolePlay);
            $em->flush();

            $this->addFlash('success', $translator->trans('role_play.flash.rp_edited'));

            return $this->redirectToRoute('rpg_game_view', [
                'locationSlug' => $location->getSlug(),
                'gameSlug' => $game->getSlug()
            ]);
        }

        return $this->render('rpg/role_play/new.html.twig', [
            'submitValue' => $translator->trans('role_play.actions.edit'),
            'game' => $game,
            'location' => $location,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param User $user
     * @param Game $game
     * @param JoiningGameModel $joiningGame
     * @return FormInterface
     */
    private function getJoiningGameForm(User $user, Game $game, JoiningGameModel $joiningGame) : FormInterface
    {
        return $this->createForm(JoiningGameFormType::class, $joiningGame, [
            'user_personas' => $user->getPersonas(),
            'action_url' => $this->generateUrl('rpg_game_join', [
                'locationSlug' => $game->getLocation()->getSlug(),
                'gameSlug' => $game->getSlug()
            ])
        ]);
    }
}
