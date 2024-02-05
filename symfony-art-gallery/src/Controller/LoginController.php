<?php

namespace Sergo\ArtGallery\Controller;

use Sergo\ArtGallery\Form\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Контроллер для аутентификации
 */
class LoginController extends AbstractController
{
    public function __invoke(Request $serverRequest, AuthenticationUtils $utils): Response
    {
        $err = $utils->getLastAuthenticationError();
        $formLogin = $this->createForm(LoginForm::class);
        $formLogin->setData(['login' => $utils->getLastUsername()]);
        $context['form_login'] = $formLogin;
        $context['err'] = $err;
        return $this->renderForm('login.twig', $context);
    }
}
