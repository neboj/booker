<?php
namespace App\Services;

use App\Entity\Booking;
use App\Notifications\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

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
     * @var SessionService
     */
    private $sessionService;
    /**
     * @var Security
     */
    private $security;


    /**
     * BookingService constructor.
     * @param EntityManagerInterface $entityManager
     * @param EmailSender $emailSender
     */
    public function __construct(EntityManagerInterface $entityManager,EmailSender $emailSender, SessionService $sessionService, Security $security)
    {
        $this->emailSender = $emailSender;
        $this->entityManager = $entityManager;
        $this->sessionService = $sessionService;
        $this->security = $security;
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
    public function saveNewBooking(Booking $booking): void {
        $this->save($booking);
        $this->emailSender->sendCreatedBookingMail($booking, [$booking->getUser()->getEmail(), EmailSender::ADMIN_EMAIL]);
    }

    /**
     * @param Booking $booking
     */
    public function save(Booking $booking): void {
        $this->entityManager->persist($booking);
        $this->entityManager->flush();
    }

    /**
     * @param int $bookingId
     * @return Booking|null
     */
    public function getBookingById(int $bookingId): ?Booking {
        return $this->entityManager->getRepository(Booking::class)->findOneBy(['id' => $bookingId]);
    }

    public function update(Booking $oldBooking, Booking $booking): void {
        $booking->setLastUpdatedTimestamp(new \DateTime());
        $this->save($booking);
        $this->emailSender->sendEditedBookingMail($oldBooking, $booking, [EmailSender::ADMIN_EMAIL]);
        $this->sessionService->denyAccessToEditBooking();
    }

    public function getAllBookings($user)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $bookings = $this->entityManager->getRepository(Booking::class)->findAll();
        } else {
            $bookings = $this->getAllBookingsForSpecificUser($user->getId());
        }
        return $bookings;
    }

    public function getAllBookingsForSpecificUser(int $userId)
    {
        return $this->entityManager->getRepository(Booking::class)->findBy(['user' => $userId]);
    }


}