<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        TranslatorInterface $translator
    ){
        if ($request->isMethod('POST')) {
            $user = new User();
            $user->setEmail($request->request->get('email'));
            $user->setUsername($request->request->get('username'));
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', $translator->trans('common.register_success'));
            return $this->redirectToRoute('home');
        }

        return $this->render('security/register.html.twig');
    }

    /**
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @param TranslatorInterface $translator
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/forgotten-password", name="app_forgotten_password")
     */
    public function forgottenPassword(
        Request $request,
        \Swift_Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator,
        TranslatorInterface $translator
    ){
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');


            $entityManager = $this->getDoctrine()->getManager();
            /** @var User $user */
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);

            if (null === $user) {
                $this->addFlash('danger', $translator->trans('forgotten_password.email_not_found'));
                return $this->redirectToRoute('home');
            }

            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('home');
            }

            $resetPasswordUrl = $this->generateUrl(
                'app_reset_password',
                ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $message = (new \Swift_Message('Forgot Password'))
                ->setFrom($this->getParameter('contact_email'))
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/lost_password.html.twig',
                        [
                            'resetPasswordUrl' => $resetPasswordUrl,
                            'username' => $user->getUsername()
                        ]
                    ),
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('info', $translator->trans('forgotten_password.email_sent'));
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/reset-password/{token}", name="app_reset_password")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param TranslatorInterface $translator
     * @param $token
     * @return Response
     */
    public function resetPassword(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        TranslatorInterface $translator,
        $token
    ){
        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            /** @var User $user */
            $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);

            if (null === $user) {
                $this->addFlash('danger', $translator->trans('reset_password.invalid_token'));

                return $this->redirectToRoute('home');
            }

            $user->setResetToken(null);
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $request->request->get('password'))
            );
            $entityManager->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour');

            return $this->redirectToRoute('home');
        }

        return $this->render('security/reset_password.html.twig');
    }
}
