<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $em
    )
    {
        parent::__construct($registry, Product::class);
    }

    public function create(Product $product, bool $flush = true): Product
    {
        $this->em->persist($product);
        if ($flush) {
            $this->em->flush();
        }
        return $product;
    }

    public function update(Product $product, bool $flush = true): Product
    {
        $this->em->persist($product);
        if ($flush) {
            $this->em->flush();
        }
        return $product;
    }

    public function delete(Product $product, bool $flush = true): void
    {
        $this->em->remove($product);
        if ($flush) {
            $this->em->flush();
        }
    }

}
