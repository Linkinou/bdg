<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
}
