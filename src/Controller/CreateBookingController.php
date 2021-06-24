<?php

namespace App\Controller;

use App\Form\BookingType;
use App\Services\BookingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateBookingController extends AbstractController
{
    /**
     * @Route("/create/booking", name="create_booking")
     * @param Request $request
     * @param BookingService $bookingService
     * @return Response
     */
    public function index(Request $request, BookingService $bookingService): Response
    {
        $booking = $bookingService->create($this->getUser());
        $form = $this->createForm(BookingType::class, $booking, ['is_admin' => $this->isGranted('ROLE_ADMIN')]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bookingService->saveBooking($form->getData());
            $this->addFlash('success', 'Booking created successfully!');
        }
        return $this->render('create_booking/index.html.twig', [
            'controller_name' => 'CreateBookingController',
            'form' => $form->createView()
        ]);
    }
}
