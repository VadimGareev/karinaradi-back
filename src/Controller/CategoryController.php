<?php

namespace App\Controller;

use App\DTOValidator\CategoryDTOValidator;
use App\DTOValidator\ProductDTOValidator;
use App\Entity\Category;
use App\Entity\Product;
use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
use App\ResponseBuilder\CategoryResponseBuilder;
use App\ResponseBuilder\ProductResponseBuilder;
use App\Service\CategoryService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryService $categoryService,
        private CategoryResponseBuilder $categoryResponseBuilder,
        private CategoryDTOValidator $categoryDTOValidator,
        private CategoryFactory $categoryFactory
    )
    {
    }

    #[Route('/api/categories', name: 'categories_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $categories = $this->categoryService->index();
        return $this->categoryResponseBuilder->indexResponse($categories);
    }

    #[Route('/api/categories/{category}', name: 'categories_show', methods: ['GET'])]
    public function show(Category $category): JsonResponse
    {
        return $this->categoryResponseBuilder->showResponse($category);
    }

    #[Route('/api/categories', name: 'categories_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $createCategoryInputDTO = $this->categoryFactory->makeCreateCategoryInputDTO($data);
        $this->categoryDTOValidator->validate($createCategoryInputDTO);
        $category = $this->categoryService->create($createCategoryInputDTO);
        return $this->categoryResponseBuilder->createResponse($category);
    }

    #[Route('/api/categories/{category}', name: 'categories_update', methods: ['PATCH'])]
    public function update(Category $category,Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $updateCategoryInputDTO = $this->categoryFactory->makeUpdateCategoryInputDTO($data);
        $this->categoryDTOValidator->validate($updateCategoryInputDTO);
        $category = $this->categoryService->update($category, $updateCategoryInputDTO);
        return $this->categoryResponseBuilder->updateResponse($category);
    }

    #[Route('/api/categories/{category}', name: 'categories_delete', methods: ['DELETE'])]
    public function delete(Category $category): JsonResponse
    {
        $this->categoryService->delete($category);
        return $this->categoryResponseBuilder->deleteResponse();
    }
}
