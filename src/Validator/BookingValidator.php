<?php

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BookingValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        $originalEntity = $this->entityManager
            ->getUnitOfWork()
            ->getOriginalEntityData($this->context->getObject());

        $oldDate = new \DateTime(); //newly created booking
        if (isset($originalEntity['datetime'])) {
            $oldDate = $originalEntity['datetime']; //editing existing booking
        }

        $todayPlus3 = (new \DateTime())->add(new \DateInterval('P3D'));

        $newDate = $this->context->getValue();

        $daysDifference = (int) ($newDate->diff((new \DateTime()))->format("%a"));


        /* @var $constraint \App\Validator\Booking */

        if (null === $value || '' === $value) {
            return;
        }
        $dateInPast = new \DateTime() > $newDate;
        $dateHasUpdated = $oldDate->format('Y-m-d') != $newDate->format('Y-m-d');
        $lessThan3Days = $daysDifference < 3;
        if (($dateInPast || $lessThan3Days)  && $dateHasUpdated) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $todayPlus3->format('d.m.Y'))
                ->atPath('datetime')
                ->addViolation();
        }
    }
}
