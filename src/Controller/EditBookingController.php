<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Notifications\EmailSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class EditBookingController extends AbstractController
{

    /**
     * @Route("/edit/booking/{bookingId}", name="edit_booking")
     * @param Request $request
     * @param int $bookingId
     * @param EmailSender $emailSender
     * @param SessionInterface $session
     * @return Response
     */
    public function index(Request $request, int $bookingId, EmailSender $emailSender, SessionInterface $session): Response
    {
        $this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_USER']);

        /** @var Booking|null $booking */
        $booking = $this->getDoctrine()->getRepository(Booking::class)->find($bookingId);
        if (!$booking) {
            return $this->redirectToRoute('list_bookings');
        }

        if ($this->isGranted('ROLE_USER') && $booking->getUser()->getId() != $this->getUser()->getId()) {
            return $this->redirectToRoute('list_bookings');
        }

        if ($this->isGranted('ROLE_USER')) {
            $hasPassedAuth = $session->get('hasPassedAuth') && ($session->get('hasPassedAuthForBookingId') == $bookingId);
            if (!$hasPassedAuth) {
                return $this->redirectToRoute('edit_booking_auth', ['bookingId' => $bookingId]);
            }
        }

        $booking->setUser($booking->getUser());
        $oldBooking = clone $booking;

        $form = $this->createForm(BookingType::class, $booking, ['is_admin' => $this->isGranted('ROLE_ADMIN')]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $booking = $form->getData();
            $booking->setLastUpdatedTimestamp(new \DateTime());

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);
            $em->flush();

            $emailSender->sendEditedBookingMail($oldBooking, $booking, [EmailSender::ADMIN_EMAIL]);

            $session->remove('hasPassedAuth');
            $session->remove('hasPassedAuthForBookingId');
            return $this->redirectToRoute('list_bookings');
        }
        return $this->render('edit_booking/index.html.twig', [
            'controller_name' => 'EditBookingController',
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }
}
