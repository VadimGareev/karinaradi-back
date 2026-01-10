<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class EntityExists extends Constraint
{
    public string $entity;
    public string $message = 'Entity "{{ entity }}" with ID "{{ value }}" does not exist.';

    public function __construct($entity, mixed $options = null, ?array $groups = null, mixed $payload = null)
    {
        parent::__construct($options, $groups, $payload);
        $this->entity = $entity;
    }

    public function validatedBy(): string
    {
        return EntityExistsValidator::class;
    }
}
