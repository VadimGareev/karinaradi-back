<?php

namespace App\Service;

use App\DTO\Input\Product\CreateProductInputDTO;
use App\DTO\Input\Product\UpdateProductInputDTO;
use App\Entity\Product;
use App\Entity\ProductImages;
use App\Factory\ProductFactory;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Psr\Log\LoggerInterface;

class ProductService
{
    public function __construct(
        private ProductRepository $productRepository,
        private ProductFactory $productFactory,
        private EntityManagerInterface $em,
        private string $uploadsDirectory,
        private ?LoggerInterface $logger = null
    ) {
    }

    /** Сохранить файл и вернуть относительный путь для фронта (например: /uploads/abcd.jpg) */
    private function saveUploadedFile(UploadedFile $file): string
    {
        $ext = $file->guessExtension() ?: $file->getClientOriginalExtension() ?: 'bin';
        $filename = bin2hex(random_bytes(8)) . '.' . $ext;

        if (!is_dir($this->uploadsDirectory)) {
            @mkdir($this->uploadsDirectory, 0775, true);
        }

        $file->move($this->uploadsDirectory, $filename);

        return '/uploads/' . $filename;
    }

    /** Удалить файл из диска, если существует. Принимает путь вида '/uploads/abc.jpg' или 'uploads/abc.jpg' */
    private function removeFileIfExists(?string $path): void
    {
        if (!$path) return;

        $filename = basename($path);
        $full = rtrim($this->uploadsDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        if (file_exists($full)) {
            @unlink($full);
        }
    }

    /** Универсальный setter для главного изображения — попытается вызвать подходящий метод */
    private function setProductMainImage(Product $product, string $path): void
    {
        $candidates = ['setImage', 'setPath', 'setImagePath', 'setMainImage'];
        foreach ($candidates as $m) {
            if (method_exists($product, $m)) {
                $product->$m($path);
                return;
            }
        }
        if (property_exists($product, 'image')) {
            $product->setImage($path);
        } elseif (property_exists($product, 'path')) {
            $product->path = $path;
        }
    }

    private function getProductMainImage(Product $product): ?string
    {
        $candidates = ['getImage', 'getPath', 'getImagePath', 'getMainImage'];
        foreach ($candidates as $m) {
            if (method_exists($product, $m)) {
                return $product->$m();
            }
        }
        if (property_exists($product, 'image')) {
            return $product->getImage();
        }
        if (property_exists($product, 'path')) {
            return $product->path;
        }
        return null;
    }

    public function create(CreateProductInputDTO $createProductInputDTO, ?UploadedFile $imageFile = null, array $productImageFiles = []): Product
    {
        $product = $this->productFactory->makeProduct($createProductInputDTO);

        if ($imageFile instanceof UploadedFile) {
            try {
                $path = $this->saveUploadedFile($imageFile);
                $this->setProductMainImage($product, $path);
            } catch (\Throwable $e) {
                if ($this->logger) $this->logger->error('Error saving main image: '.$e->getMessage());
                throw $e;
            }
        }

        $product = $this->productRepository->create($product);

        foreach ($productImageFiles as $file) {
            if (!$file instanceof UploadedFile) continue;
            try {
                $imgPath = $this->saveUploadedFile($file);
                $pi = new ProductImages();
                if (method_exists($pi, 'setPath')) {
                    $pi->setPath($imgPath);
                } else {
                    if (property_exists($pi, 'path')) $pi->setPath($imgPath);
                }
                if (method_exists($pi, 'setProduct')) {
                    $pi->setProduct($product);
                } else {
                    $pi->setProduct($product);
                }
                $this->em->persist($pi);
                if (method_exists($product, 'addProductImage')) {
                    $product->addProductImage($pi);
                } else {
                    if (method_exists($product, 'getProductImages')) {
                        $coll = $product->getProductImages();
                        if (is_object($coll) && method_exists($coll, 'add')) {
                            $coll->add($pi);
                        }
                    }
                }
            } catch (\Throwable $e) {
                if ($this->logger) $this->logger->error('Error saving product image: '.$e->getMessage());
                throw $e;
            }
        }

        $this->em->flush();

        return $product;
    }

    public function index()
    {
        return $this->productRepository->findAll();
    }

    /**
     * @param Product $product
     * @param UpdateProductInputDTO $updateProductInputDTO
     * @param UploadedFile|null $imageFile
     * @param array $productImageFiles array of UploadedFile
     * @param array $productImagesToDelete array of ProductImages ids to delete
     * @return Product
     */
    public function update(
        Product $product,
        UpdateProductInputDTO $updateProductInputDTO,
        ?UploadedFile $imageFile = null,
        array $productImageFiles = [],
        array $productImagesToDelete = []
    ): Product
    {
        $product = $this->productFactory->editProduct($product, $updateProductInputDTO);

        if ($imageFile instanceof UploadedFile) {
            $old = $this->getProductMainImage($product);
            if ($old) $this->removeFileIfExists($old);

            $path = $this->saveUploadedFile($imageFile);
            $this->setProductMainImage($product, $path);
        }

        foreach ($productImageFiles as $file) {
            if (!$file instanceof UploadedFile) continue;
            $imgPath = $this->saveUploadedFile($file);
            $pi = new ProductImages();
            if (method_exists($pi, 'setPath')) {
                $pi->setPath($imgPath);
            } else {
                if (property_exists($pi, 'path')) $pi->setPath($imgPath);
            }
            if (method_exists($pi, 'setProduct')) {
                $pi->setProduct($product);
            } else {
                $pi->setProduct($product);
            }
            $this->em->persist($pi);
            if (method_exists($product, 'addProductImage')) {
                $product->addProductImage($pi);
            } elseif (method_exists($product, 'getProductImages')) {
                $coll = $product->getProductImages();
                if (is_object($coll) && method_exists($coll, 'add')) {
                    $coll->add($pi);
                }
            }
        }

        if (!empty($productImagesToDelete)) {
            $repo = $this->em->getRepository(ProductImages::class);
            foreach ($productImagesToDelete as $imgId) {
                $pi = $repo->find($imgId);
                if (!$pi) continue;
                $belongs = false;
                if (method_exists($pi, 'getProduct')) {
                    $belongs = $pi->getProduct() && $pi->getProduct()->getId() === $product->getId();
                } elseif (property_exists($pi, 'product')) {
                    $belongs = $pi->getProduct() && $pi->getProduct()->getId() === $product->getId();
                }
                if ($belongs) {
                    if (method_exists($pi, 'getPath')) {
                        $this->removeFileIfExists($pi->getPath());
                    } elseif (property_exists($pi, 'path')) {
                        $this->removeFileIfExists($pi->getPath());
                    }
                    $this->em->remove($pi);
                }
            }
        }

        $product = $this->productRepository->update($product);

        $this->em->flush();

        return $product;
    }

    public function delete(Product $product): void
    {
        $repo = $this->em->getRepository(ProductImages::class);
        $images = $repo->findBy(['product' => $product]);
        foreach ($images as $pi) {
            if (method_exists($pi, 'getPath')) {
                $this->removeFileIfExists($pi->getPath());
            } elseif (property_exists($pi, 'path')) {
                $this->removeFileIfExists($pi->getPath());
            }
            $this->em->remove($pi);
        }

        $main = $this->getProductMainImage($product);
        if ($main) {
            $this->removeFileIfExists($main);
        }

        $this->em->flush();

        $this->productRepository->delete($product);
    }
}
