<?php

namespace App\Controller;

use App\Form\BookingType;
use App\Services\BookingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditBookingController extends AbstractController
{

    /**
     * @Route("/edit/booking/{bookingId}", name="edit_booking")
     * @param Request $request
     * @param int $bookingId
     * @param BookingService $bookingService
     * @return Response
     */
    public function index(Request $request, int $bookingId, BookingService $bookingService): Response
    {
        $booking = $bookingService->getBookingById($bookingId);
        if (!$booking) return $this->redirectToRoute('list_bookings');
        $this->denyAccessUnlessGranted('edit', $booking);

        $form = $this->createForm(BookingType::class, $booking, ['is_admin' => $this->isGranted('ROLE_ADMIN')]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bookingService->update($booking, $form->getData());
            return $this->redirectToRoute('list_bookings');
        }

        return $this->render('edit_booking/index.html.twig', [
            'controller_name' => 'EditBookingController',
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }
}
