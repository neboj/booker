<?php
declare(strict_types=1);

namespace App\Services;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class SessionService
 * @package App\Services
 */
class SessionService
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface  $session)
    {
        $this->session = $session;
    }

    /**
     * Allow user to edit this booking
     *
     * @param int $bookingId
     */
    public function grantAccessToEditBooking(int $bookingId)
    {
        $this->session->set('hasPassedAuth', true);
        $this->session->set('hasPassedAuthForBookingId', $bookingId);
    }

    public function denyAccessToEditBooking()
    {
        $this->session->remove('hasPassedAuth');
        $this->session->remove('hasPassedAuthForBookingId');
    }
}