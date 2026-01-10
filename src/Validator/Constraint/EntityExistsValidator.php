<?php

namespace App\Validator\Constraint;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class EntityExistsValidator extends ConstraintValidator
{
    public function __construct(private EntityManagerInterface $em)
    {

    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof EntityExists) {
            throw new UnexpectedTypeException($constraint, EntityExists::class);
        }

        $ids = is_array($value) ? $value : [$value];

        $repo = $this->em->getRepository($constraint->entity);

        foreach ($ids as $id) {
            if (!$repo->find($id)) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', (string) $id)
                    ->setParameter('{{ entity }}', $constraint->entity)
                    ->addViolation();
            }
        }
    }

}
