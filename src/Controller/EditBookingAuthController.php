<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\EditBookingAuthFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class EditBookingAuthController extends AbstractController
{
    /**
     * @Route("/edit/booking/auth/{bookingId}", name="edit_booking_auth")
     * @param Request $request
     * @param int $bookingId
     * @param SessionInterface $session
     * @return Response
     */
    public function index(Request $request, int $bookingId, SessionInterface $session): Response
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


        $form = $this->createForm(EditBookingAuthFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            // Check auth (used booking id as auth id)
            $booking = $this->getDoctrine()->getRepository(Booking::class)->findOneBy(['id' => $formData['authId'], 'user' => $this->getUser()->getId()]);

            if ($booking && $bookingId === $formData['authId']) {
                $session->set('hasPassedAuth', true);
                $session->set('hasPassedAuthForBookingId', $bookingId);
                return $this->redirectToRoute('edit_booking', ['bookingId' => $bookingId]);
            }

            $this->addFlash('error', 'Wrong auth key');
        }
        return $this->render('edit_booking_auth/index.html.twig', [
            'controller_name' => 'EditBookingAuthController',
            'form' => $form->createView()
        ]);
    }
}
