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
        /** @var User $user */
//        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
        $bookingsAll = $this->getDoctrine()->getRepository(Booking::class)->findBy(['user' => $this->getUser()]);
        $user1 = $bookingsAll[0]->getUser();
//        $bookings = $user->getBookings();

        return $this->render('list_bookings/index.html.twig', [
            'controller_name' => 'ListBookingsController',
//            'user' => $user,
//            'bookings' => $bookings,
            'bookingsAll' => $bookingsAll,
            'user1' => $user1
        ]);
    }
}
