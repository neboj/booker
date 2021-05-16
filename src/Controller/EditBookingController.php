<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
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
     * @return Response
     */
    public function index(Request $request, int $bookingId): Response
    {
        $this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_USER']);

        $booking = $this->getDoctrine()->getRepository(Booking::class)->find($bookingId);

        if ($this->isGranted('ROLE_USER') && $booking->getUser()->getId() != $this->getUser()->getId()) {
            return $this->redirectToRoute('list_bookings');
        }

        $booking->setUser($booking->getUser());

        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $booking = $form->getData();
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);
            $em->flush();

//            $this->addFlash('success', 'Booking created successfully!');
            return $this->redirectToRoute('list_bookings');
        }

        return $this->render('edit_booking/index.html.twig', [
            'controller_name' => 'EditBookingController',
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }
}
