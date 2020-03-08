<?php

namespace App\Repository;

use App\Entity\Promotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Promotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promotion[]    findAll()
 * @method Promotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
    }

    public function save(Promotion $promotion)
    {
        try{
            $this->_em->persist($promotion);
            $this->_em->flush();

            return true;
        }catch (\Exception $e){

            return $e->getMessage();
        }
    }

    public function update(Promotion $promotion){
        try{
            $this->_em->merge($promotion);
            $this->_em->flush();

            return true;
        }catch (\Exception $e){

            return $e->getMessage();
        }
    }
}
