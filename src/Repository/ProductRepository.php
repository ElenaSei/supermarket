<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $product)
    {
        try{
            $this->_em->persist($product);
            $this->_em->flush();

            return true;
        }catch (\Exception $e){

            return $e->getMessage();
        }
    }

    public function update(Product $product){
        try{
            $this->_em->merge($product);
            $this->_em->flush();

            return true;
        }catch (\Exception $e){

            return $e->getMessage();
        }
    }

    public function delete(Product $product){
        try{
            $this->_em->remove($product);
            $this->_em->flush();

            return true;
        }catch (\Exception $e){

            return $e->getMessage();
        }
    }
}
