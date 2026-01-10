<?php

namespace App\Resource;

use App\DTO\OutputDTO\Category\CategoryOutputDTO;
use App\DTO\OutputDTO\Product\ProductOutputDTO;
use App\Entity\Product;
use Symfony\Component\Serializer\SerializerInterface;

class CategoryResource
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function item(CategoryOutputDTO $category): string
    {
        return $this->serializer->serialize($category, 'json', ['groups' => ['category:item']]);
    }

    public function items(array $categories): string
    {
        return $this->serializer->serialize($categories, 'json', ['groups' => ['category:item']]);
    }
}
