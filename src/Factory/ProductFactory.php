<?php

namespace App\Factory;

use App\DTO\Input\Product\CreateProductInputDTO;
use App\DTO\Input\Product\UpdateProductInputDTO;
use App\DTO\OutputDTO\Product\ProductOutputDTO;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductImages;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFactory
{
    public function __construct(
        private EntityManagerInterface $em,
        private SluggerInterface $slugger
    )
    {
    }

    public function makeProduct(CreateProductInputDTO $createProductInputDTO): Product
    {
        $product = new Product();
        $slug = $this->slugger->slug($createProductInputDTO->title);
        $product->setSlug($slug);
        foreach ($createProductInputDTO->categories as $category) {
            $product->addCategory($this->em->getReference(Category::class, $category));
        }

        // TODO: Надо изменить, не будет работать
        foreach ($createProductInputDTO->productImages as $image) {
            $product->addProductImage($this->em->getReference(ProductImages::class, $image));
        }

        $product->setTitle($createProductInputDTO->title);
        $product->setPrice($createProductInputDTO->price);
        $product->setArticle($createProductInputDTO->article);
        $product->setDescription($createProductInputDTO->description);
        $product->setImage($createProductInputDTO->image);
        $product->setCare($createProductInputDTO->care);
        $product->setMeasurements($createProductInputDTO->measurements);
        $product->setSlug($this->slugger->slug($createProductInputDTO->title));
        $product->setModelParams($createProductInputDTO->model_params);

        return $product;
    }

    public function editProduct(Product $product,UpdateProductInputDTO $updateProductInputDTO): Product
    {
        foreach ($updateProductInputDTO->categories as $category) {
            $product->addCategory($this->em->getReference(Category::class, $category));
        }

        // TODO: Надо изменить, не будет работать
        foreach ($updateProductInputDTO->productImages as $image) {
            $product->addProductImage($this->em->getReference(ProductImages::class, $image));
        }

        $product->setTitle($updateProductInputDTO->title);
        $product->setPrice($updateProductInputDTO->price);
        $product->setArticle($updateProductInputDTO->article);
        $product->setDescription($updateProductInputDTO->description);
        $product->setImage($updateProductInputDTO->image);
        $product->setCare($updateProductInputDTO->care);
        $product->setMeasurements($updateProductInputDTO->measurements);
        $product->setSlug($this->slugger->slug($updateProductInputDTO->title));
        $product->setModelParams($updateProductInputDTO->model_params);

        return $product;
    }

    public function makeCreateProductInputDTO(array $data): CreateProductInputDTO
    {
        $createProductInputDTO = new CreateProductInputDTO();

        $createProductInputDTO->title = $data['title'] ?? null;
        $createProductInputDTO->price = $data['price'] ?? null;
        $createProductInputDTO->article = $data['article'] ?? null;
        $createProductInputDTO->description = $data['description'] ?? null;
        $createProductInputDTO->image = $data['image'] ?? null;
        $createProductInputDTO->care = $data['care'] ?? null;
        $createProductInputDTO->measurements = $data['measurements'] ?? null;
        $createProductInputDTO->model_params = $data['modelParams'] ?? null;
        $createProductInputDTO->categories = $data['categories'] ?? null;
        $createProductInputDTO->productImages = $data['productImages'] ?? null;

        return $createProductInputDTO;
    }

    public function makeCreateProductOutputDTO(Product $product): ProductOutputDTO
    {
        $productOutputDTO = new ProductOutputDTO();

        $productOutputDTO->id = $product->getId();
        $productOutputDTO->title = $product->getTitle();
        $productOutputDTO->price = $product->getPrice();
        $productOutputDTO->article = $product->getArticle();
        $productOutputDTO->description = $product->getDescription();
        $productOutputDTO->image = $product->getImage();
        $productOutputDTO->care = $product->getCare();
        $productOutputDTO->measurements = $product->getMeasurements();
        $productOutputDTO->slug = $product->getSlug();
        $productOutputDTO->model_params = $product->getModelParams();
        $productOutputDTO->categories = $product->getCategories();
        $productOutputDTO->productImages = $product->getProductImages();

        return $productOutputDTO;
    }

    public function makeProductOutputDTOCollection(array $products): array
    {
        return array_map(fn($product) => $this->makeCreateProductOutputDTO($product), $products);
    }

    public function makeUpdateProductInputDTO(array $data): UpdateProductInputDTO
    {
        $updateProductInputDTO = new UpdateProductInputDTO();

        $updateProductInputDTO->title = $data['title'] ?? null;
        $updateProductInputDTO->price = $data['price'] ?? null;
        $updateProductInputDTO->article = $data['article'] ?? null;
        $updateProductInputDTO->description = $data['description'] ?? null;
        $updateProductInputDTO->image = $data['image'] ?? null;
        $updateProductInputDTO->care = $data['care'] ?? null;
        $updateProductInputDTO->measurements = $data['measurements'] ?? null;
        $updateProductInputDTO->model_params = $data['modelParams'] ?? null;
        $updateProductInputDTO->categories = $data['categories'] ?? null;
        $updateProductInputDTO->productImages = $data['productImages'] ?? null;

        return $updateProductInputDTO;
    }

    public function makeUpdateProductOutputDTO(Product $product): ProductOutputDTO
    {
        $productOutputDTO = new ProductOutputDTO();

        $productOutputDTO->id = $product->getId();
        $productOutputDTO->title = $product->getTitle();
        $productOutputDTO->price = $product->getPrice();
        $productOutputDTO->article = $product->getArticle();
        $productOutputDTO->description = $product->getDescription();
        $productOutputDTO->image = $product->getImage();
        $productOutputDTO->care = $product->getCare();
        $productOutputDTO->measurements = $product->getMeasurements();
        $productOutputDTO->slug = $product->getSlug();
        $productOutputDTO->model_params = $product->getModelParams();
        $productOutputDTO->categories = $product->getCategories();
        $productOutputDTO->productImages = $product->getProductImages();

        return $productOutputDTO;
    }
}
