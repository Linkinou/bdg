<?php

namespace App\Controller\Website;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Topic;
use App\FormType\TopicCreationFormType;
use App\Model\TopicPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}/new-topic", name="category_new_topic")
     */
    public function new(Request $request, Category $category, TranslatorInterface $translator)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $newTopicPost = new TopicPost();
        $form = $this->createForm(TopicCreationFormType::class, $newTopicPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newTopic = new Topic();
            $newPost = new Post();

            $newTopic
                ->setAuthor($this->getUser())
                ->setCategory($category)
                ->setTitle($newTopicPost->getTopicTitle())
            ;

            $newPost
                ->setAuthor($this->getUser())
                ->setTopic($newTopic)
                ->setContent($newTopicPost->getContent())
            ;

            $em = $this->getDoctrine()->getManager();
            $em->persist($newTopic);
            $em->persist($newPost);
            $em->flush();

            $this->addFlash('success', $translator->trans('topic.alert.success'));

            return $this->redirectToRoute('category_view', [
                'slug' => $category->getSlug()
            ]);
        }

        return $this->render('topic/new.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/category/{slug}", name="category_view")
     */
    public function index(Category $category)
    {
        $topics = $this->getDoctrine()->getRepository(Topic::class)->findLatest($category->getId());

        return $this->render('category/index.html.twig', [
            'category' => $category,
            'topics' => $topics
        ]);
    }
}
