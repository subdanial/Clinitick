<?php

namespace App\Controller;

use App\Service\CoreApi\AuthApi;
use Ghasedak\GhasedakApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/auth", name="auth.")
 */
class AuthController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {
        return $this->render('auth/login.html.twig');
    }

    /**
     * @Route("/reset", name="reset")
     *
     * @return Response
     */
    public function forgotPassword(): Response
    {
        return $this->render('auth/test.html.twig');
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     *
     * @return Response
     * @throws \Exception
     */
    public function logout(): Response
    {
        throw new \Exception('Problem in logging out');
    }
}
