<?php

namespace App\Service;

use App\DTO\Input\Category\CreateCategoryInputDTO;
use App\DTO\Input\Category\UpdateCategoryInputDTO;
use App\Entity\Category;
use App\Factory\CategoryFactory;
use App\Repository\CategoryRepository;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private CategoryFactory $categoryFactory,
    )
    {
    }

    public function create(CreateCategoryInputDTO $createCategoryInputDTO): Category
    {
        $category = $this->categoryFactory->makeCategory($createCategoryInputDTO);
        return $this->categoryRepository->create($category);
    }

    public function index()
    {
        return $this->categoryRepository->findAll();
    }

    public function update(Category $category, UpdateCategoryInputDTO $updateCategoryInputDTO): Category
    {
        $category = $this->categoryFactory->editCategory($category ,$updateCategoryInputDTO);
        return $this->categoryRepository->update($category);
    }

    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }
}
