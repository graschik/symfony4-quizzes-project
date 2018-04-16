<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\RecoveryPassword\CreateEmailRequest;
use App\Entity\RecoveryPassword\CreateRecoveryPassword;
use App\Entity\User;
use App\Form\LoginType;
use App\Form\RecoveryPassword\RecoveryTypeEmail;
use App\Form\RecoveryPassword\RecoveryTypePassword;
use App\Form\SignupType;
use App\Service\FormHandler\FinishPasswordRecoveryFormHandler;
use App\Service\FormHandler\SignupFormHandler;
use App\Service\FormHandler\StartPasswordRecoveryFormHandler;
use App\Service\Security\RegisterConfirmer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class SecurityController extends Controller
{
    /**
     * @Route("/login",name="login")
     *
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(
        Request $request,
        AuthenticationUtils $authenticationUtils
    ): Response
    {
        $form = $this->createForm(LoginType::class, new User());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('home');
        }

        $authenticationError = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'authenticationError' => $authenticationError,
            'lastUsername' => $lastUsername
        ]);
    }


    /**
     * @Route("/signup",name="signup")
     *
     * @param Request $request
     * @param SignupFormHandler $signupFormHandler
     * @return Response
     */
    public function signup(
        Request $request,
        SignupFormHandler $signupFormHandler
    ): Response
    {
        $form = $this->createForm(SignupType::class, new User());
        if ($signupFormHandler->handle($form, $request)) {
            $this->addFlash(
                'success',
                'На вашу почту отправлено сообщение с дальнейшей инструкцией регистрации'
            );
            return $this->redirectToRoute('home');
        }

        return $this->render('security/signup.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/register/confirm/{token}",name="confirm")
     *
     * @param string $token
     * @param RegisterConfirmer $registerConfirmer
     * @return Response
     */
    public function registerConfirm(
        string $token,
        RegisterConfirmer $registerConfirmer
    ): Response
    {
        $user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['token' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Access denied!');
        }

        $registerConfirmer->confirm($user);

        return $this->redirect('/login');
    }


    /**
     * @Route("/recovery-password-1", name="recovery.password.email")
     *
     * @param Request $request
     * @param StartPasswordRecoveryFormHandler $formHandler
     * @return Response
     */
    public function startRecovery(
        Request $request,
        StartPasswordRecoveryFormHandler $formHandler
    ): Response
    {
        $form = $this->createForm(RecoveryTypeEmail::class, new CreateEmailRequest());
        if ($formHandler->handle($form, $request)) {
            $this->addFlash(
                'success',
                'На вашу почту отправлено сообщение с дальнейшей инструкцией по восстановлению пароля'
            );
            return $this->redirectToRoute('home');
        }

        return $this->render('security/recovery_password_step_1.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recovery-password-2/{token}",name="recovery.password")
     *
     * @param Request $request
     * @param string $token
     * @param FinishPasswordRecoveryFormHandler $formHandler
     * @return Response
     */
    public function passwordRecovery(
        Request $request,
        string $token,
        FinishPasswordRecoveryFormHandler $formHandler
    ): Response
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['recoveryToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Access denied!');
        }

        $form = $this->createForm(RecoveryTypePassword::class, new CreateRecoveryPassword());
        if ($formHandler->handle($form, $request, ['entity' => $user])) {
            return $this->redirect('/login');
        }

        return $this->render('security/recovery_password_step_2.html.twig', [
            'form' => $form->createView(),
            'token' => $token,
        ]);
    }

}