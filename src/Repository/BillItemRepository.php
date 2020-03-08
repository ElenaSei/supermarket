<?php

namespace App\Repository;

use App\Entity\BillItem;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * @method BillItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method BillItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method BillItem[]    findAll()
 * @method BillItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillItemRepository extends \Doctrine\ORM\EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new ClassMetadata(BillItem::class));
    }

    public function save(BillItem $billItem){

        try{
            $this->_em->persist($billItem);
            $this->_em->flush();

            return true;
        }catch (\Exception $e){

            return $e->getMessage();
        }
    }

    public function update(BillItem $billItem){
        try{
            $this->_em->merge($billItem);
            $this->_em->flush();

            return true;
        }catch (\Exception $e){

            return $e->getMessage();
        }
    }
}
