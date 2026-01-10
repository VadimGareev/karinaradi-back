<?php

namespace App\ResponseBuilder;

use App\Entity\Category;
use App\Factory\CategoryFactory;
use App\Resource\CategoryResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryResponseBuilder
{
    public function __construct(
        private CategoryResource $categoryResource,
        private CategoryFactory $categoryFactory
    )
    {
    }

    public function createResponse(Category $category, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $outputDTO = $this->categoryFactory->makeCreateCategoryOutputDTO($category);
        $postResource = $this->categoryResource->item($outputDTO);
        return new JsonResponse($postResource, $status, $headers, $isJson);
    }

    public function updateResponse(Category $category, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $outputDTO = $this->categoryFactory->makeUpdateCategoryOutputDTO($category);
        $postResource = $this->categoryResource->item($outputDTO);
        return new JsonResponse($postResource, $status, $headers, $isJson);
    }

    public function indexResponse(array $categories, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $outputDTO = $this->categoryFactory->makeCategoryOutputDTOCollection($categories);
        $postResource = $this->categoryResource->items($outputDTO);
        return new JsonResponse($postResource, $status, $headers, $isJson);
    }

    public function showResponse(Category $category, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $outputDTO = $this->categoryFactory->makeCreateCategoryOutputDTO($category);
        $postResource = $this->categoryResource->item($outputDTO);
        return new JsonResponse($postResource, $status, $headers, $isJson);
    }

    public function deleteResponse($status = 200, $headers = []): JsonResponse
    {
        return new JsonResponse(['message' => 'deleted'], $status, $headers);
    }

}
