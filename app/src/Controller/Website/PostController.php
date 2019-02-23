<?php

namespace App\Controller\Website;

use App\Entity\Post;
use App\FormType\TopicReplyFormType;
use App\Model\TopicPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post/{id}/edit", name="post_edit")
     */
    public function index(Request $request, Post $post)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (null === $post) {
            $this->addFlash('info', $this->get('translator')->trans('post.flash.not_found'));

            return $this->redirectToRoute('home');
        }

        $topicPost = TopicPost::createFromPost($post);
        $form = $this->createForm(TopicReplyFormType::class, $topicPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post
                ->setContent($topicPost->getContent())
            ;

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('topic_view', [
                'slug' => $post->getTopic()->getSlug(),
                '_fragment' => $post->getId()
            ]);
        }

        return $this->render('topic/reply.html.twig', [
            'topic' => $post->getTopic(),
            'form' => $form->createView()
        ]);
    }
}
