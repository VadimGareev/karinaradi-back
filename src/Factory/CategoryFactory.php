<?php

namespace App\Factory;

use App\DTO\Input\Category\CreateCategoryInputDTO;
use App\DTO\Input\Category\UpdateCategoryInputDTO;
use App\DTO\Input\Product\CreateProductInputDTO;
use App\DTO\Input\Product\UpdateProductInputDTO;
use App\DTO\OutputDTO\Category\CategoryOutputDTO;
use App\DTO\OutputDTO\Product\ProductOutputDTO;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductImages;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryFactory
{
    public function __construct(
        private EntityManagerInterface $em,
    )
    {
    }

    public function makeCategory(CreateCategoryInputDTO $createCategoryInputDTO): Category
    {
        $category = new Category();
        foreach ($createCategoryInputDTO->categories as $categoryItem) {
            $category->addCategory($this->em->getReference(Category::class, $categoryItem));
        }

        $category->setTitle($createCategoryInputDTO->title);
        $category->setSlug($createCategoryInputDTO->slug);
        $category->setActive($createCategoryInputDTO->active);

        return $category;
    }

    public function editCategory(Category $category,UpdateCategoryInputDTO $updateCategoryInputDTO): Category
    {
        foreach ($updateCategoryInputDTO->categories as $categoryItem) {
            $category->addCategory($this->em->getReference(Category::class, $categoryItem));
        }

        $category->setTitle($updateCategoryInputDTO->title);
        $category->setSlug($updateCategoryInputDTO->slug);
        $category->setActive($updateCategoryInputDTO->active);

        return $category;
    }

    public function makeCreateCategoryInputDTO(array $data): CreateCategoryInputDTO
    {
        $createCategoryInputDTO = new CreateCategoryInputDTO();

        $createCategoryInputDTO->title = $data['title'] ?? null;
        $createCategoryInputDTO->slug = $data['slug'] ?? null;
        $createCategoryInputDTO->categories = $data['categories'] ?? null;
        $createCategoryInputDTO->active = $data['active'] ?? null;

        return $createCategoryInputDTO;
    }

    public function makeCreateCategoryOutputDTO(Category $category): CategoryOutputDTO
    {
        $categoryOutputDTO = new CategoryOutputDTO();
        $categoryOutputDTO->id = $category->getId();
        $categoryOutputDTO->title = $category->getTitle();
        $categoryOutputDTO->slug = $category->getSlug();
        $categoryOutputDTO->categories = $category->getCategories();
        $categoryOutputDTO->active = $category->isActive();

        return $categoryOutputDTO;
    }

    public function makeCategoryOutputDTOCollection(array $categories): array
    {
        return array_map(fn($category) => $this->makeCreateCategoryOutputDTO($category), $categories);
    }

    public function makeUpdateCategoryInputDTO(array $data): UpdateCategoryInputDTO
    {
        $updateCategoryInputDTO = new UpdateCategoryInputDTO();

        $updateCategoryInputDTO->title = $data['title'] ?? null;
        $updateCategoryInputDTO->slug = $data['slug'] ?? null;
        $updateCategoryInputDTO->categories = $data['categories'] ?? null;
        $updateCategoryInputDTO->active = $data['active'] ?? null;

        return $updateCategoryInputDTO;
    }

    public function makeUpdateCategoryOutputDTO(Category $category): CategoryOutputDTO
    {
        $categoryOutputDTO = new CategoryOutputDTO();
        $categoryOutputDTO->id = $category->getId();
        $categoryOutputDTO->title = $category->getTitle();
        $categoryOutputDTO->slug = $category->getSlug();
        $categoryOutputDTO->categories = $category->getCategories();
        $categoryOutputDTO->active = $category->isActive();

        return $categoryOutputDTO;
    }
}
