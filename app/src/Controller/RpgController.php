<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Location;
use App\Entity\RolePlay;
use App\FormType\GameCreationFormType;
use App\Model\GameModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

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
    public function viewGame(Request $request, TranslatorInterface $translator, $locationSlug, $gameSlug)
    {
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

        $page = $request->query->get('page', 1);
        $rolePlays = $this->getDoctrine()->getRepository(RolePlay::class)->findLatest($game, $page);

        return $this->render('rpg/game.html.twig', [
            'game' => $game,
            'rolePlays' => $rolePlays
        ]);
    }
}
