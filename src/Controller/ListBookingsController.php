<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListBookingsController extends AbstractController
{
    /**
     * @Route("/list/bookings", name="list_bookings")
     */
    public function index(): Response
    {
        $bookings = $this->getDoctrine()->getRepository(Booking::class)->findBy(['user' => $this->getUser()]);

        return $this->render('list_bookings/index.html.twig', [
            'controller_name' => 'ListBookingsController',
            'bookings' => $bookings
        ]);
    }
}
