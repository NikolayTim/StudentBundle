<?php

namespace StudentBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsValidStudentId extends Constraint
{
    public $message = 'Не найден студент с Id: ';
}