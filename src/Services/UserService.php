<?php
declare(strict_types=1);

namespace App\Services;


use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @throws \App\Exceptions\EntityUniqueConstraintException
     */
    public function saveNewUser(\App\Entity\User $user)
    {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        $user->setRoles(['ROLE_USER']);
        $this->save($user);
    }

    /**
     * @throws \App\Exceptions\EntityUniqueConstraintException
     */
    public function save(\App\Entity\User $user) {
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new \App\Exceptions\EntityUniqueConstraintException(['entity_name' => get_class($user),'message' => $e->getMessage()]);
        }
    }
}