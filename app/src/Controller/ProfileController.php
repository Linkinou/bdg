<?php


namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     *
     * @Route("/{slug}", name="profile")
     */
    public function profile(User $user)
    {
        return $this->render('profile/index.html.twig', [
            'user' => $user
        ]);
    }
}