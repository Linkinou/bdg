<?php

namespace App\Controller\Website;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function Home()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAllParents();

        return $this->render("home/index.html.twig", [
            'categories' => $categories
        ]);
    }
}