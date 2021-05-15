<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $datetime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $commentUser;

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
     * @param User $user
     * @return Booking
     */
    public function setUser(User $user): self
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
}
