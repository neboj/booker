<?php


namespace App\Notifications;


use App\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailSender extends AbstractController
{

    const ADMIN_EMAIL = 'nebojsa.kozlovacki@gmail.com';

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendCreatedBookingMail(Booking $booking, $addresses) {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('test@gmail.com')
            ->setTo($addresses)
            ->setSubject('Booker - created new booking')
            ->setBody(
                $this->renderView(
                    'emails/registration.html.twig',
                    [
                        'booking' => $booking
                    ]
                ),
                'text/html'
            );
        return $this->mailer->send($message);
    }

    public function sendEditedBookingMail(Booking $oldBooking, Booking $newBooking, $addresses) {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('test@gmail.com')
            ->setTo($addresses)
            ->setSubject('Booker - edited booking')
            ->setBody(
                $this->renderView(
                    'emails/edited.html.twig',
                    [
                        'oldBooking' => $oldBooking,
                        'newBooking' => $newBooking
                    ]
                ),
                'text/html'
            );
        return $this->mailer->send($message);
    }

}