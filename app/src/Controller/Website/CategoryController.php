<?php

namespace App\Controller\Website;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{id}", name="view_category")
     */
    public function index($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        return $this->render('category/index.html.twig', [
            'category' => $category,
        ]);
    }
}
