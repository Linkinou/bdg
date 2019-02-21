<?php

namespace App\Controller\Website;

use App\Entity\Topic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends AbstractController
{
    /**
     * @Route("/topic/{id}", name="topic_view")
     */
    public function index($id)
    {
        $topic = $this->getDoctrine()->getRepository(Topic::class)->find($id);
        return $this->render('topic/index.html.twig', [
            'topic' => $topic,
        ]);
    }
}
