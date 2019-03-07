<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Location;
use App\Entity\Persona;
use App\Entity\RolePlay;
use App\Entity\User;
use App\FormType\GameCreationFormType;
use App\Model\GameModel;
use App\Model\GameStateButton;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Workflow\Registry;

/**
 * @Route("/rpg")
 */
class RpgController extends AbstractController
{
    /**
     * @Route("/", name="rpg_homepage")
     */
    public function index()
    {
        $locations = $this->getDoctrine()->getRepository(Location::class)->findAll();

        return $this->render('rpg/index.html.twig', [
            'locations' => $locations,
        ]);
    }

    /**
     * @Route("/{slug}", name="rpg_location_view")
     */
    public function viewLocation(Request $request, Location $location)
    {
        $page = $request->query->get('page', 1);
        $games = $this->getDoctrine()->getRepository(Game::class)->findLatest($location->getId(), $page);

        return $this->render('rpg/location.html.twig', [
            'location' => $location,
            'games' => $games
        ]);
    }


    /**
     * @Route("/new-game", name="rpg_new_game")
     * @Route("/{slug}/new-game", name="rpg_new_game_from_location")
     */
    public function createGame(Request $request, TranslatorInterface $translator, Location $location = null)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $gameModel = new GameModel();

        if (null !== $location) {
            $gameModel->setLocation($location);
        }

        $form = $this->createForm(GameCreationFormType::class, $gameModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newGame = new Game();
            $newGame
                ->setLocation($gameModel->getLocation())
                ->setTitle($gameModel->getTitle())
                ->setDescription($gameModel->getDescription())
                ->setStory($gameModel->getStory())
                ->setMaxPlayingPersonas($gameModel->getMaxPlayingPersonas())
                ->setGameMaster($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($newGame);
            $em->flush();

            $this->addFlash('success', $translator->trans('topic.alert.success'));

            return $this->redirectToRoute('rpg_location_view', [
                'slug' => $newGame->getLocation()->getSlug()
            ]);
        }

        return $this->render('rpg/new_game.html.twig', [
            'form' => $form->createView(),
            'location' => $location
        ]);
    }

    /**
     * @Route("/{locationSlug}/{gameSlug}", name="rpg_view_game")
     */
    public function viewGame(
        Request $request,
        TranslatorInterface $translator,
        $locationSlug,
        $gameSlug
    ){
        $location = $this->getDoctrine()->getRepository(Location::class)->findOneBy(['slug' => $locationSlug]);
        if (null === $location) {
            $this->addFlash('warning', $translator->trans('common.flash.not_found'));

            return $this->redirectToRoute('home');
        }

        $game = $this->getDoctrine()->getRepository(Game::class)->findOneBy(['slug' => $gameSlug]);
        if (null === $game) {
            $this->addFlash('warning', $translator->trans('common.flash.not_found'));

            return $this->redirectToRoute('rpg_location_view', ['slug' => $locationSlug]);
        }

//        if ($game->getState() === 'draft' && !$game->isGameMaster($this->getUser())) {
//            $this->addFlash('warning', $translator->trans('common.flash.not_found'));
//
//            return $this->redirectToRoute('rpg_location_view', ['slug' => $locationSlug]);
//        }

        $page = $request->query->get('page', 1);
        $rolePlays = $this->getDoctrine()->getRepository(RolePlay::class)->findLatest($game, $page);

        return $this->render('rpg/game.html.twig', [
            'game' => $game,
            'rolePlays' => $rolePlays,
            'gameStateButton' => $this->getGameStateButton($translator, $game)
        ]);
    }

    /**
     * @Route("/{locationSlug}/{gameSlug}/leave", name="rpg_leave_game")
     */
    public function leaveGame(TranslatorInterface $translator, $locationSlug, $gameSlug)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $location = $this->getDoctrine()->getRepository(Location::class)->findOneBy(['slug' => $locationSlug]);
        if (null === $location) {
            $this->addFlash('warning', $translator->trans('common.flash.not_found'));

            return $this->redirectToRoute('home');
        }

        $game = $this->getDoctrine()->getRepository(Game::class)->findOneBy(['slug' => $gameSlug]);
        if (null === $game) {
            $this->addFlash('warning', $translator->trans('common.flash.not_found'));

            return $this->redirectToRoute('rpg_location_view', ['slug' => $locationSlug]);
        }

        /** @var User $user */
        $user = $this->getUser();
        $game->removePendingPersona($user->getPersonas()->first());
        $game->removePlayingPersona($user->getPersonas()->first());
        $em = $this->getDoctrine()->getManager();
        $em->persist($game);
        $em->flush();

        $this->addFlash('success', $translator->trans('game.flash.left'));

        return $this->redirectToRoute('rpg_view_game', [
            'locationSlug' => $locationSlug,
            'gameSlug' => $gameSlug
        ]);
    }

    /**
     * @Route("/{locationSlug}/{gameSlug}/join", name="rpg_join_game")
     */
    public function joinGame(TranslatorInterface $translator, $locationSlug, $gameSlug)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $location = $this->getDoctrine()->getRepository(Location::class)->findOneBy(['slug' => $locationSlug]);
        if (null === $location) {
            $this->addFlash('warning', $translator->trans('common.flash.not_found'));

            return $this->redirectToRoute('home');
        }

        $game = $this->getDoctrine()->getRepository(Game::class)->findOneBy(['slug' => $gameSlug]);
        if (null === $game) {
            $this->addFlash('warning', $translator->trans('common.flash.not_found'));

            return $this->redirectToRoute('rpg_location_view', ['slug' => $locationSlug]);
        }

        /** @var User $user */
        $user = $this->getUser();
        $registeredPersonas = array_merge($game->getPendingPersonas()->toArray(), $game->getPlayingPersonas()->toArray());
        if (!empty(array_intersect($registeredPersonas, $user->getPersonas()->toArray()))) {
            $this->addFlash('warning', $translator->trans('game.flash.already_joined'));

            return $this->redirectToRoute('rpg_view_game', [
                'locationSlug' => $locationSlug,
                'gameSlug' => $gameSlug
            ]);
        }

        $game->addPendingPersona($user->getPersonas()->first());
        $em = $this->getDoctrine()->getManager();
        $em->persist($game);
        $em->flush();

        $this->addFlash('success', $translator->trans('game.flash.joined'));

        return $this->redirectToRoute('rpg_view_game', [
            'locationSlug' => $locationSlug,
            'gameSlug' => $gameSlug
        ]);
    }

    /**
     * @param TranslatorInterface $translator
     * @param Game $game
     * @return GameStateButton|null
     */
    private function getGameStateButton(TranslatorInterface $translator, Game $game)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (null === $user) {
            return null;
        }

        $playingPersonas = $game->getPlayingPersonas();
        /** @var Persona $persona */
        foreach ($playingPersonas as $persona) {
            if ($persona->getUser() === $user) {
                return new GameStateButton(
                    $translator->trans('game.text.leave_group'),
                    $translator->trans('game.text.accepted'),
                    $this->generateUrl('rpg_leave_game', [
                        'locationSlug' => $game->getLocation()->getSlug(),
                        'gameSlug' => $game->getSlug()
                    ])
                );
            }
        }

        $pendingPersonas = $game->getPendingPersonas();
        /** @var Persona $persona */
        foreach ($pendingPersonas as $persona) {
            if ($persona->getUser() === $user) {
                return new GameStateButton(
                    $translator->trans('game.text.leave_group'),
                    $translator->trans('game.text.pending_validation'),
                    $this->generateUrl('rpg_leave_game', [
                        'locationSlug' => $game->getLocation()->getSlug(),
                        'gameSlug' => $game->getSlug()
                    ])
                );
            }
        }

        return new GameStateButton(
            $translator->trans('game.text.join_group'),
            $translator->trans('game.text.can_join'),
            $this->generateUrl('rpg_join_game', [
                'locationSlug' => $game->getLocation()->getSlug(),
                'gameSlug' => $game->getSlug()
            ])
        );
    }
}
