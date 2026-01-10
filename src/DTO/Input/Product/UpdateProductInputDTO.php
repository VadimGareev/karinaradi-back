<?php

namespace App\DTO\Input\Product;

use App\Entity\Category;
use App\Entity\ProductImages;
use App\Validator\Constraint\EntityExists;

class UpdateProductInputDTO
{
    #[Assert\NotBlank(allowNull: null, normalizer: 'trim')]
    #[Assert\Length(min: 1, max: 255)]
    public ?string $title = null;

    #[Assert\NotBlank(allowNull: null, normalizer: 'trim')]
    #[Assert\Length(min: 1, max: 255)]
    public ?string $slug = null;

    #[Assert\NotNull]
    #[Assert\Type(type: 'float')]
    public ?float $price = null;

    #[Assert\NotBlank(allowNull: null, normalizer: 'trim')]
    public ?string $article = null;

    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    public ?string $image = null;

    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    public ?string $description = null;

    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    public ?string $care = null;

    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    public ?string $measurements = null;

    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    public ?string $model_params = null;

    /**
     * @var int[]
     */
    #[Assert\NotNull]
    #[Assert\Type('array')]
    #[Assert\Count(min: 1, minMessage: 'Должна быть выбрана хотя бы одна категория')]
    #[Assert\All([
        new Assert\NotBlank,
        new Assert\Type('integer'),
    ])]
    #[EntityExists(entity: Category::class)]
    public array $categories = [];

    #[EntityExists(entity: ProductImages::class)]
    #[Assert\Type('array')]
    public array $productImages = [];
}
