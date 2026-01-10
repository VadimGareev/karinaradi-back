<?php

namespace App\Controller;

use App\DTOValidator\ProductDTOValidator;
use App\Entity\Product;
use App\Factory\ProductFactory;
use App\ResponseBuilder\ProductResponseBuilder;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    public function __construct(
        private ProductService $productService,
        private ProductResponseBuilder $productResponseBuilder,
        private ProductDTOValidator $productDTOValidator,
        private ProductFactory $productFactory
    )
    {
    }

    #[Route('/api/products', name: 'products_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $products = $this->productService->index();
        return $this->productResponseBuilder->indexResponse($products);
    }

    #[Route('/api/products/{product}', name: 'products_show', methods: ['GET'])]
    public function show(Product $product): JsonResponse
    {
        return $this->productResponseBuilder->showResponse($product);
    }

    #[Route('/api/products', name: 'products_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = $request->request->all();
        if (!isset($data['productImages']) || $data['productImages'] === null) {
            $data['productImages'] = [];
        }
        if (isset($data['categories']) && is_array($data['categories'])) {
            $data['categories'] = array_map('intval', $data['categories']);
        } else {
            $data['categories'] = [];
        }

        $createProductInputDTO = $this->productFactory->makeCreateProductInputDTO($data);
        $this->productDTOValidator->validate($createProductInputDTO);

        /** @var UploadedFile|null $imageFile */
        $imageFile = $request->files->get('image');

        $productImages = $request->files->get('productImages', []);
        if ($productImages instanceof UploadedFile) {
            $productImages = [$productImages];
        }

        $product = $this->productService->create($createProductInputDTO, $imageFile, $productImages);

        return $this->productResponseBuilder->createResponse($product);
    }

    #[Route('/api/products/{product}', name: 'products_update', methods: ['PATCH'])]
    public function update(Product $product,Request $request): JsonResponse
    {
        $data = $request->request->all();
        $updateProductInputDTO = $this->productFactory->makeUpdateProductInputDTO($data);
        $this->productDTOValidator->validate($updateProductInputDTO);

        /** @var UploadedFile|null $imageFile */
        $imageFile = $request->files->get('image');

        $productImages = $request->files->get('productImages', []);
        if ($productImages instanceof UploadedFile) {
            $productImages = [$productImages];
        }

        $productImagesToDelete = [];
        if ($request->request->has('productImagesToDelete')) {
            $productImagesToDelete = json_decode($request->request->get('productImagesToDelete'), true) ?: [];
        }

        $product = $this->productService->update($product, $updateProductInputDTO, $imageFile, $productImages, $productImagesToDelete);

        return $this->productResponseBuilder->updateResponse($product);
    }

    #[Route('/api/products/{product}', name: 'products_delete', methods: ['DELETE'])]
    public function delete(Product $product): JsonResponse
    {
        $this->productService->delete($product);
        return $this->productResponseBuilder->deleteResponse();
    }
}
