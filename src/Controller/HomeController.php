<?php

declare(strict_types=1);

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{

    /**
     * @Route("/",name="home.index")
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->redirectToRoute('quiz.show');
    }

    /**
     * @Route("/home",name="home")
     *
     * @return Response
     */
    public function homeAction(): Response
    {
        return $this->render('home/home.html.twig');
    }
}