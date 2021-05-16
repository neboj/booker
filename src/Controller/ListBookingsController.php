<?php

namespace App\Controller;

use App\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListBookingsController extends AbstractController
{
    /**
     * @Route("/list/bookings/", name="list_bookings")
     * @return Response
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_USER']);

        $bookings = $this->getDoctrine()->getRepository(Booking::class)->findBy(['user' => $this->getUser()->getId()]);

        if ($this->isGranted('ROLE_ADMIN')) {
            $bookings = $this->getDoctrine()->getRepository(Booking::class)->findAll();
        }

        return $this->render('list_bookings/index.html.twig', [
            'controller_name' => 'ListBookingsController',
            'bookings' => $bookings
        ]);
    }


    /**
     * @Route("/list/bookings/{userId}", name="list_bookings_by_user")
     * @param int $userId
     * @return Response
     */
    public function userBookings(int $userId): Response
    {
        $this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_USER']);
        
        if ($this->isGranted('ROLE_USER') && $userId != $this->getUser()->getId()) {
            return $this->redirectToRoute('list_bookings');
        }

        $bookings = $this->getDoctrine()->getRepository(Booking::class)->findBy(['user' => $userId]);

        return $this->render('list_bookings/index.html.twig', [
            'controller_name' => 'ListBookingsController',
            'bookings' => $bookings
        ]);
    }
}
