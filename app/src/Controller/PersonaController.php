<?php


namespace App\Controller;


use App\Entity\Persona;
use App\FormType\PersonaFormType;
use App\Model\PersonaModel;
use App\Service\PersonaService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/persona")
 */
class PersonaController extends AbstractController
{
    /**
     * @Route("/create", name="persona_create")
     */
    public function createPersona(
        Request $request,
        PersonaService $personaService,
        EntityManagerInterface $em,
        TranslatorInterface $translator
    ){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $personaModel = new PersonaModel();

        $form = $this->createForm(PersonaFormType::class, $personaModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $persona = $personaService->createPersona($personaModel, $this->getUser());
            $em->persist($persona);
            $em->flush();

            $this->addFlash('success', $translator->trans('persona.flash.persona_created'));

            return $this->redirectToRoute('profile', [
                'slug' => $this->getUser()->getSlug()
            ]);
        }

        return $this->render('persona/new.html.twig', [
            'form' => $form->createView(),
            'submitValue' => $translator->trans('persona.actions.create'),
        ]);
    }

    /**
     * @Route("/{slug}", name="persona_view")
     */
    public function index(Persona $persona)
    {
        return $this->render('persona/index.html.twig',[
            'persona' => $persona
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="persona_edit")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param Persona $persona
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editPersona(
        Request $request,
        TranslatorInterface $translator,
        EntityManagerInterface $em,
        PersonaService $personaService,
        Persona $persona
    ) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $personaModel = PersonaModel::createFromPersona($persona);
        $form = $this->createForm(PersonaFormType::class, $personaModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $persona = $personaService->editPersona($personaModel, $persona);

            $em->flush();

            $this->addFlash('success', $translator->trans('persona.flash.persona_edited'));

            return $this->redirectToRoute('persona_view', [
                'slug' => $persona->getSlug(),
            ]);
        }

        return $this->render('persona/new.html.twig', [
            'form' => $form->createView(),
            'submitValue' => $translator->trans('persona.actions.edit')
        ]);
    }
}