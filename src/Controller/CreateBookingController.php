<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateBookingController extends AbstractController
{
    /**
     * @Route("/create/booking", name="create_booking")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $booking = new Booking();
        $booking->setCommentUser('');
        $booking->setCommentAdmin('');
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $booking->setUser($user);


        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $booking = $form->getData();
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($booking);
            $em->flush();

            $this->addFlash('success', 'Booking created successfully!');
//            return $this->redirectToRoute('homepage');
        }
        return $this->render('create_booking/index.html.twig', [
            'controller_name' => 'CreateBookingController',
            'form' => $form->createView(null)
        ]);
    }
}
