<?php

namespace App\Controller;

use App\Form\EditBookingAuthFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditBookingAuthController extends AbstractController
{
    /**
     * @Route("/edit/booking/auth/{bookingId}", name="edit_booking_auth")
     * @param Request $request
     * @param int $bookingId
     * @param \App\Services\AuthenticationService $authenticationService
     * @param \App\Services\BookingService $bookingService
     * @return Response
     */
    public function index(
        Request $request,
        int $bookingId,
        \App\Services\AuthenticationService $authenticationService,
        \App\Services\BookingService  $bookingService
    ): Response {
        $booking = $bookingService->getBookingById($bookingId);
        if (!$booking) return $this->redirectToRoute('list_bookings');
        $this->denyAccessUnlessGranted('view', $booking);

        $form = $this->createForm(EditBookingAuthFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $valid = $authenticationService->checkAuthKey($form->getData(), $bookingId);
            if ($valid) return $this->redirectToRoute('edit_booking', ['bookingId' => $bookingId]);
            $this->addFlash('error', 'Wrong auth key');
        }

        return $this->render('edit_booking_auth/index.html.twig', [
            'controller_name' => 'EditBookingAuthController',
            'form' => $form->createView()
        ]);
    }
}
