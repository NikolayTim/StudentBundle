<?php

namespace StudentBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsValidTaskId extends Constraint
{
    public $message = 'Не найдено задание с Id: ';
}