<?php

namespace App\DTO\OutputDTO\Product;

use App\Entity\Category;
use App\Entity\ProductImages;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Attribute\Groups;

class ProductOutputDTO
{
    #[Groups(groups: ['product:item'])]
    public ?int $id = null;

    #[Groups(groups: ['product:item'])]
    public ?string $title = null;

    #[Groups(groups: ['product:item'])]
    public ?string $slug = null;

    #[Groups(groups: ['product:item'])]
    public ?float $price = null;

    #[Groups(groups: ['product:item'])]
    public ?string $article = null;

    #[Groups(groups: ['product:item'])]
    public ?string $image = null;

    #[Groups(groups: ['product:item'])]
    public ?string $description = null;

    #[Groups(groups: ['product:item'])]
    public ?string $care = null;

    #[Groups(groups: ['product:item'])]
    public ?string $measurements = null;

    #[Groups(groups: ['product:item'])]
    public ?string $model_params = null;

    /**
     * @var Collection<int, Category>
     */
    #[Groups(groups: ['product:item'])]
    public Collection $categories;

    /**
     * @var Collection<int, ProductImages>
     */
    #[Groups(groups: ['product:item'])]
    public Collection $productImages;

}
