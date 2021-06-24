<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @\App\Validator\Booking()
     */
    private $datetime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $commentUser;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastUpdatedTimestamp;

    /**
     * @return mixed
     */
    public function getLastUpdatedTimestamp()
    {
        return $this->lastUpdatedTimestamp;
    }

    /**
     * @param mixed $lastUpdatedTimestamp
     */
    public function setLastUpdatedTimestamp($lastUpdatedTimestamp): void
    {
        $this->lastUpdatedTimestamp = $lastUpdatedTimestamp;
    }

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDatetime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @var User
     */
    private $user;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     * @return Booking
     */
    public function setUser(UserInterface $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommentUser()
    {
        return $this->commentUser;
    }

    /**
     * @param mixed $commentUser
     */
    public function setCommentUser($commentUser): void
    {
        $this->commentUser = $commentUser;
    }

    /**
     * @return mixed
     */
    public function getCommentAdmin()
    {
        return $this->commentAdmin;
    }

    /**
     * @param mixed $commentAdmin
     */
    public function setCommentAdmin($commentAdmin): void
    {
        $this->commentAdmin = $commentAdmin;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $commentAdmin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreationDatetime()
    {
        return $this->creationDatetime;
    }

    /**
     * @param mixed $creationDatetime
     */
    public function setCreationDatetime($creationDatetime): void
    {
        $this->creationDatetime = $creationDatetime;
    }
}
