<?php

namespace App\Services;

use App\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class StoreService
{
    public function __construct(private readonly ManagerRegistry $entityManager) {
    }

    public function discoverBrandByName(string $brandName): Brand {
  
        $doctrine = $this->entityManager->getManager();
        $brand = $this->entityManager->getRepository(Brand::class)->findOneBy(['name' => $brandName]);

        if ($brand === null) {
            $brand = new Brand();
            $brand->setName($brandName);
            $doctrine->persist($brand);
            $doctrine->flush();
        }

        return $brand;
    }
}
