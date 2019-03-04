<?php

namespace App\Controller;

use App\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/{slug}", name="rpg_view_location")
     */
    public function viewLocation(Location $location)
    {
        return $this->render('rpg/index.html.twig', [
            'locations' => $locations,
        ]);
    }
}
