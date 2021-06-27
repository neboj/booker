<?php
declare(strict_types=1);

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AuthenticationService
 * @package App\Services
 */
class AuthenticationService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    private $session;
    /**
     * @var SessionService
     */
    private $sessionService;

    /**
     * AuthenticationService constructor.
     * @param EntityManagerInterface $entityManager
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function __construct(EntityManagerInterface $entityManager, \Symfony\Component\HttpFoundation\Session\SessionInterface $session, \App\Services\SessionService $sessionService)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->sessionService = $sessionService;
    }

    /**
     * @param array $data
     * @param int $bookingId
     * @return bool
     */
    public function checkAuthKey(array $data, int $bookingId): bool {
        $result = false;
        $authKey = $data['authId'];
        $valid = $bookingId === $authKey;

        if ($valid) {
            $this->sessionService->grantAccessToEditBooking($bookingId);
            $result = true;
        }
        return $result;
    }
}