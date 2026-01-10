<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, Category::class);
    }

    public function create(Category $product, bool $flush = true): Category
    {
        $this->em->persist($product);
        if ($flush) {
            $this->em->flush();
        }
        return $product;
    }

    public function update(Category $product, bool $flush = true): Category
    {
        $this->em->persist($product);
        if ($flush) {
            $this->em->flush();
        }
        return $product;
    }

    public function delete(Category $product, bool $flush = true): void
    {
        $this->em->remove($product);
        if ($flush) {
            $this->em->flush();
        }
    }
}
