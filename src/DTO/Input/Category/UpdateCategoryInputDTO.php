<?php

namespace App\DTO\Input\Category;

use App\Entity\Category;
use App\Validator\Constraint\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateCategoryInputDTO
{

    #[Assert\NotBlank(allowNull: null, normalizer: 'trim')]
    #[Assert\Length(min: 1, max: 255)]
    public ?string $title = null;

    #[Assert\NotBlank(allowNull: null, normalizer: 'trim')]
    #[Assert\Length(min: 1, max: 255)]
    public ?string $slug = null;

    #[Assert\Type('array')]
    #[EntityExists(entity: Category::class)]
    public array $categories = [];

    #[Assert\Type(type: 'bool')]
    public ?bool $active = null;
}
