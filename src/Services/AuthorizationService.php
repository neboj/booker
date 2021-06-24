<?php
declare(strict_types=1);

namespace App\Services;


use App\Entity\Booking;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class AuthorizationService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * AuthorizationService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws \App\Exceptions\UnauthorizedAccessException
     * @throws \App\Exceptions\NotExistingResource
     */
    public function denyAccessToUnlessGrantedUser(int $bookingId, \Symfony\Component\Security\Core\User\UserInterface $user)
    {
        /** @var Booking|null $booking */
        $booking = $this->entityManager->getRepository(Booking::class)->find($bookingId);

        if (!$booking)
            throw new \App\Exceptions\NotExistingResource();

        /** @var User user */
        if ($booking->getUser()->getId() != $user->getId())
            throw new \App\Exceptions\UnauthorizedAccessException();
    }
}