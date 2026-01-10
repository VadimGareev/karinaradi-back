<?php

namespace App\Resource;

use App\DTO\OutputDTO\Product\ProductOutputDTO;
use App\Entity\Product;
use Symfony\Component\Serializer\SerializerInterface;

class ProductResource
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function item(ProductOutputDTO $product): string
    {
        return $this->serializer->serialize($product, 'json', ['groups' => ['product:item']]);
    }

    public function items(array $products): string
    {
        return $this->serializer->serialize($products, 'json', ['groups' => ['product:item']]);
    }
}
