<?php

namespace App\Controller\Website;

use App\Entity\Post;
use App\Entity\Topic;
use App\FormType\TopicReplyFormType;
use App\Model\TopicPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends AbstractController
{
    /**
     * @Route("/topic/{slug}", methods={"GET"}, name="topic_view")
     */
    public function index(Request $request, Topic $topic)
    {
        $page = $request->query->get('page', 1);
        $latestPosts = $this->getDoctrine()->getRepository(Post::class)->findLatest($topic->getId(), $page);

        return $this->render('/topic/index.html.twig', [
            'topic' => $topic,
            'posts' => $latestPosts
        ]);
    }

    /**
     * @Route("/topic/{slug}/reply", name="topic_reply")
     */
    public function reply(Request $request, Topic $topic)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $topicPost = new TopicPost();
        $form = $this->createForm(TopicReplyFormType::class, $topicPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reply = new Post();
            $reply
                ->setContent($topicPost->getContent())
                ->setTopic($topic)
                ->setAuthor($this->getUser())
            ;

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reply);
            $entityManager->flush();

            return $this->redirectToRoute('topic_view', [
                'slug' => $topic->getSlug(),
                '_fragment' => 'last'
            ]);
        }

        return $this->render('/topic/reply.html.twig', [
            'topic' => $topic,
            'form' => $form->createView()
        ]);
    }
}
