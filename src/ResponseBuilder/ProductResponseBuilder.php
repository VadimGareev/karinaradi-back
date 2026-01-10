<?php

namespace App\ResponseBuilder;

use App\Entity\Product;
use App\Factory\ProductFactory;
use App\Resource\ProductResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductResponseBuilder
{
    public function __construct(
        private ProductResource $productResource,
        private ProductFactory $productFactory
    )
    {
    }

    public function createResponse(Product $product, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $outputDTO = $this->productFactory->makeCreateProductOutputDTO($product);
        $postResource = $this->productResource->item($outputDTO);
        return new JsonResponse($postResource, $status, $headers, $isJson);
    }

    public function updateResponse(Product $product, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $outputDTO = $this->productFactory->makeUpdateProductOutputDTO($product);
        $postResource = $this->productResource->item($outputDTO);
        return new JsonResponse($postResource, $status, $headers, $isJson);
    }

    public function indexResponse(array $products, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $outputDTO = $this->productFactory->makeProductOutputDTOCollection($products);
        $postResource = $this->productResource->items($outputDTO);
        return new JsonResponse($postResource, $status, $headers, $isJson);
    }

    public function showResponse(Product $product, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $outputDTO = $this->productFactory->makeCreateProductOutputDTO($product);
        $postResource = $this->productResource->item($outputDTO);
        return new JsonResponse($postResource, $status, $headers, $isJson);
    }

    public function deleteResponse($status = 200, $headers = []): JsonResponse
    {
        return new JsonResponse(['message' => 'deleted'], $status, $headers);
    }

}
