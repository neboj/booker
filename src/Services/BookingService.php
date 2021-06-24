<?php
namespace App\Services;

use App\Entity\Booking;
use App\Notifications\EmailSender;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class BookingService
 * @package App\Services
 */
class BookingService {
    /**
     * @var EmailSender
     */
    private $emailSender;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    /**
     * BookingService constructor.
     * @param EntityManagerInterface $entityManager
     * @param EmailSender $emailSender
     */
    public function __construct(EntityManagerInterface $entityManager,EmailSender $emailSender)
    {

        $this->emailSender = $emailSender;
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @return Booking
     */
    public function create(\Symfony\Component\Security\Core\User\UserInterface $user): Booking
    {
        $booking = new Booking();
        $booking->setCommentUser('');
        $booking->setCommentAdmin('');
        $booking->setLastUpdatedTimestamp(new \DateTime());
        $booking->setCreationDatetime(new \DateTime());
        $booking->setUser($user);
        return $booking;
    }

    /**
     * @param Booking $booking
     */
    public function saveBooking(Booking $booking): void {
        $this->entityManager->persist($booking);
        $this->entityManager->flush();
        $this->emailSender->sendCreatedBookingMail($booking, [$booking->getUser()->getEmail(), EmailSender::ADMIN_EMAIL]);
    }
}