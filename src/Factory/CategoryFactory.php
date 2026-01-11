<?php

namespace App\Factory;

use App\DTO\Input\Category\CreateCategoryInputDTO;
use App\DTO\Input\Category\UpdateCategoryInputDTO;
use App\DTO\OutputDTO\Category\CategoryOutputDTO;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFactory
{
    public function __construct(
        private EntityManagerInterface $em,
        private SluggerInterface $slugger
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
        $category->setSlug($this->slugger->slug($createCategoryInputDTO->title));
        $category->setActive($createCategoryInputDTO->active);

        return $category;
    }

    public function editCategory(Category $category,UpdateCategoryInputDTO $updateCategoryInputDTO): Category
    {
        foreach ($updateCategoryInputDTO->categories as $categoryItem) {
            $category->addCategory($this->em->getReference(Category::class, $categoryItem));
        }

        $category->setTitle($updateCategoryInputDTO->title);
        $category->setSlug($this->slugger->slug($updateCategoryInputDTO->title));
        $category->setActive($updateCategoryInputDTO->active);

        return $category;
    }

    public function makeCreateCategoryInputDTO(array $data): CreateCategoryInputDTO
    {
        $createCategoryInputDTO = new CreateCategoryInputDTO();

        $createCategoryInputDTO->title = $data['title'] ?? null;
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

        $related = $category->getCategory()->toArray();
        $categoryOutputDTO->categories = array_map(
            fn(Category $c) => [
                'id' => $c->getId(),
                'title' => $c->getTitle(),
                'slug' => $c->getSlug(),
            ],
            $related
        );

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
