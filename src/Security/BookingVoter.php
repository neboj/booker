<?php
declare(strict_types=1);

namespace App\Security;


use App\Entity\Booking;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BookingVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';

    private $decisionManager;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(AccessDecisionManagerInterface $decisionManager, SessionInterface $session)
    {
        $this->decisionManager = $decisionManager;
        $this->session = $session;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        // only vote on Booking objects inside this voter
        if (!$subject instanceof Booking) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // ROLE_ADMIN can do anything
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return true;
        }

        // you know $subject is a Booking object, thanks to supports
        /** @var Booking $booking */
        $booking = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($booking, $user);
            case self::EDIT:
                return $this->canEdit($booking, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     */
    private function canView(Booking $booking, User $user): bool
    {
        return $this->isOwner($booking, $user);
    }

    private function canEdit(Booking $booking, User $user)
    {
        $hasPassedAuth = $this->session->get('hasPassedAuth') && ($this->session->get('hasPassedAuthForBookingId') == $booking->getId());

        return $this->canView($booking,$user) && $hasPassedAuth;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     */
    private function isOwner(Booking $booking, User $user): bool {
        static $isOwner = null;
        if (!isset($isOwner)) {
            $isOwner = $user === $booking->getUser();
        }
        return $isOwner;
    }
}