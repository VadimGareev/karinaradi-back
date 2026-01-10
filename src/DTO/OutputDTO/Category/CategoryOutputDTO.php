<?php

namespace App\DTO\OutputDTO\Category;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductImages;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Attribute\Groups;

class CategoryOutputDTO
{
    #[Groups(groups: ['category:item'])]
    public ?int $id = null;

    #[Groups(groups: ['category:item'])]
    public ?string $title = null;

    #[Groups(groups: ['category:item'])]
    public ?string $slug = null;

    #[Groups(groups: ['category:item'])]
    public Collection $category;

    #[Groups(groups: ['category:item'])]
    public Collection $categories;

    #[Groups(groups: ['category:item'])]
    public ?bool $active = null;

    public Collection $products;

}
