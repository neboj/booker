<?php

namespace App\Controller;

use App\Services\BookingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListBookingsController extends AbstractController
{

    /**
     * @var BookingService
     */
    private $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * @Route("/list/bookings/", name="list_bookings")
     * @return Response
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_USER']);

        $bookings = $this->bookingService->getAllBookings($this->getUser());

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
        $bookings = $this->bookingService->getAllBookingsForSpecificUser($userId);
        if (!$bookings) return $this->redirectToRoute('list_bookings');
        $this->denyAccessUnlessGranted('view', $bookings[0]);

        return $this->render('list_bookings/index.html.twig', [
            'controller_name' => 'ListBookingsController',
            'bookings' => $bookings
        ]);
    }
}
