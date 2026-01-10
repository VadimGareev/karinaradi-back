<?php

namespace App\DTOValidator;

use App\DTO\Input\Category\CreateCategoryInputDTO;
use App\DTO\Input\Category\UpdateCategoryInputDTO;
use App\Exception\ValidateException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryDTOValidator
{
    public function __construct(private ValidatorInterface $validator)
    {

    }

    public function validate(CreateCategoryInputDTO|UpdateCategoryInputDTO $product): void
    {
        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()][] = $error->getMessage();
            }
            throw new ValidateException($messages);
        }
    }
}
