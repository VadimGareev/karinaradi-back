<?php

namespace App\DTOValidator;

use App\DTO\Input\Product\CreateProductInputDTO;
use App\DTO\Input\Product\UpdateProductInputDTO;
use App\Entity\Product;
use App\Exception\ValidateException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductDTOValidator
{
    public function __construct(private ValidatorInterface $validator)
    {

    }

    public function validate(CreateProductInputDTO|UpdateProductInputDTO $product): void
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
