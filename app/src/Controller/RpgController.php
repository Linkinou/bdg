<?php

namespace App\Controller;

use App\Entity\Location;
use App\FormType\GameCreationFormType;
use App\Model\GameModel;
use App\Repository\GameRepository;
use App\Repository\LocationRepository;
use App\Service\GameService;
use Doctrine\ORM\EntityManagerInterface;
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

            $this->addFlash('success', $translator->trans('game.flash.game_created'));

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
}
