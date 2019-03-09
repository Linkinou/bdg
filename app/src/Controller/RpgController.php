<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Location;
use App\Entity\Persona;
use App\Entity\RolePlay;
use App\Entity\User;
use App\FormType\GameCreationFormType;
use App\FormType\JoiningGameFormType;
use App\Model\GameModel;
use App\Model\GameStateButton;
use App\Model\JoiningGameModel;
use App\Repository\GameRepository;
use App\Repository\LocationRepository;
use App\Repository\RolePlayRepository;
use App\Service\GameService;
use App\Validator\Constraints\JoiningGame;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Workflow\Registry;

/**
 * @Route("/rpg")
 */
class RpgController extends AbstractController
{
    /**
     * @Route("/", name="rpg_homepage")
     *
     * @param LocationRepository $locationRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(LocationRepository $locationRepository)
    {
        $locations = $locationRepository->findAll();

        return $this->render('rpg/index.html.twig', [
            'locations' => $locations,
        ]);
    }

    /**
     * @Route("/{slug}", name="rpg_location_view")
     */
    public function viewLocation(Request $request, GameRepository $gameRepository, Location $location)
    {
        $page = $request->query->get('page', 1);
        $games = $gameRepository->findLatest($location->getId(), $page);

        return $this->render('rpg/location.html.twig', [
            'location' => $location,
            'games' => $games
        ]);
    }


    /**
     * @Route("/new-game", name="rpg_new_game")
     * @Route("/{slug}/new-game", name="rpg_new_game_from_location")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param GameService $gameService
     * @param EntityManagerInterface $em
     * @param Location|null $location
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createGame(
        Request $request,
        TranslatorInterface $translator,
        GameService $gameService,
        EntityManagerInterface $em,
        Location $location = null
    ){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $gameModel = new GameModel();
        if (null !== $location) {
            $gameModel->setLocation($location);
        }

        $form = $this->createForm(GameCreationFormType::class, $gameModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game = $gameService->create($gameModel, $this->getUser());
            $em->persist($game);
            $em->flush();

            $this->addFlash('success', $translator->trans('topic.alert.success'));

            return $this->redirectToRoute('rpg_location_view', [
                'slug' => $game->getLocation()->getSlug()
            ]);
        }

        return $this->render('rpg/new_game.html.twig', [
            'form' => $form->createView(),
            'location' => $location
        ]);
    }

    /**
     * @Route("/{locationSlug}/{gameSlug}", name="rpg_view_game")
     * @ParamConverter("location", options={"mapping": {"locationSlug": "slug"}})
     * @ParamConverter("game", options={"mapping": {"gameSlug": "slug"}})
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param RolePlayRepository $rolePlayRepository
     * @param Location $location
     * @param Game $game
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewGame(
        Request $request,
        TranslatorInterface $translator,
        RolePlayRepository $rolePlayRepository,
        Location $location,
        Game $game
    ){
        // If draft and not owner
        if ($game->getState() === 'draft' && !$game->isGameMaster($this->getUser())) {
            throw $this->createNotFoundException($translator->trans('common.flash.not_found'));
        }

        $page = $request->query->get('page', 1);
        $rolePlays = $rolePlayRepository->findLatest($game, $page);

        /** @var User $user */
        $user = $this->getUser();
        $joiningFormView = null;
        if (null !== $user) {
            $joiningGame = new JoiningGameModel();
            $joiningFormView = $this->createForm(JoiningGameFormType::class, $joiningGame, [
                'user_personas' => $user->getPersonas(),
                'action_url' => $this->generateUrl('rpg_join_game', [
                    'locationSlug' => $location->getSlug(),
                    'gameSlug' => $game->getSlug()
                ])
            ]);
        }

        return $this->render('rpg/game.html.twig', [
            'game' => $game,
            'joiningForm' => $joiningFormView ? $joiningFormView->createView() : null,
            'rolePlays' => $rolePlays,
        ]);
    }

    /**
     * @Route("/{locationSlug}/{gameSlug}/start", name="rpg_start_game")
     * @ParamConverter("location", options={"mapping": {"locationSlug": "slug"}})
     * @ParamConverter("game", options={"mapping": {"gameSlug": "slug"}})
     * @param TranslatorInterface $translator
     * @param GameService $gameService
     * @param Location $location
     * @param Game $game
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @internal param Registry $workflows
     * @internal param EntityManagerInterface $em
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

        return $this->redirectToRoute('rpg_view_game', [
            'locationSlug' => $location->getSlug(),
            'gameSlug' => $game->getSlug()
        ]);
    }

    /**
     * @Route("/{locationSlug}/{gameSlug}/publish", name="rpg_publish_game")
     * @ParamConverter("location", options={"mapping": {"locationSlug": "slug"}})
     * @ParamConverter("game", options={"mapping": {"gameSlug": "slug"}})
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

        return $this->redirectToRoute('rpg_view_game', [
            'locationSlug' => $location->getSlug(),
            'gameSlug' => $game->getSlug()
        ]);
    }

    /**
     * @Route("/{locationSlug}/{gameSlug}/leave", name="rpg_leave_game")
     * @ParamConverter("location", options={"mapping": {"locationSlug": "slug"}})
     * @ParamConverter("game", options={"mapping": {"gameSlug": "slug"}})
     */
    public function leaveGame(TranslatorInterface $translator, GameService $gameService, Location $location, Game $game)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var User $user */
        $user = $this->getUser();

        if ($gameService->leave($game, $user)) {
            $this->addFlash('success', $translator->trans('game.flash.left'));
        }

        return $this->redirectToRoute('rpg_view_game', [
            'locationSlug' => $location->getSlug(),
            'gameSlug' => $game->getSlug()
        ]);
    }

    /**
     * @Route("/{locationSlug}/{gameSlug}/join", name="rpg_join_game", methods={"POST"})
     * @ParamConverter("location", options={"mapping": {"locationSlug": "slug"}})
     * @ParamConverter("game", options={"mapping": {"gameSlug": "slug"}})
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
        $form = $this->createForm(JoiningGameFormType::class, $joiningGame, [
            'user_personas' => $user->getPersonas(),
            'action_url' => $this->generateUrl('rpg_join_game', [
                'locationSlug' => $location->getSlug(),
                'gameSlug' => $game->getSlug()
            ])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($gameService->join($game, $user, $joiningGame->getPersona())) {
                $this->addFlash('success', $translator->trans('game.flash.joined'));
            }
        }

        return $this->redirectToRoute('rpg_view_game', [
            'locationSlug' => $location->getSlug(),
            'gameSlug' => $game->getSlug()
        ]);
    }
}
