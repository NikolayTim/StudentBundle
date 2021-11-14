<?php

namespace StudentBundle\Validator\Constraints;

use StudentBundle\Entity\Task;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Annotation
 */
final class IsValidTaskIdValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint): void
    {
        $task = $this->entityManager->getRepository(Task::class)->find($value);
        if (!($task instanceof Task))
           $this->context->buildViolation($constraint->message . $value)->addViolation();
    }
}