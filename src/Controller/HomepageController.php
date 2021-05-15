<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @param SessionInterface $session
     * @return Response
     */
    public function index(SessionInterface $session): Response
    {

        return $this->render('homepage.html.twig', [
            'controller_name' => 'HomepageController',
            'session' => $session
        ]);
    }
}
