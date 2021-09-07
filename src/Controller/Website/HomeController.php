<?php

namespace App\Controller\Website;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("", name="home")
     *
     * @return Response
     * @throws \Exception
     */
    public function home(): Response
    {
        return $this->redirectToRoute('auth.login');
    }
}
