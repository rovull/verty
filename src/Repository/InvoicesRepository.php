<?php


namespace App\Repository;

use App\Entity\Invoices;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


class InvoicesRepository extends ServiceEntityRepository
{


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoices::class);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.requestDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findAllFilter($paid)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.paid = :val')
            ->setParameter('val', $paid)
            ->orderBy('u.requestDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findByUser($user_id)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.userId = :val')
            ->setParameter('val', $user_id)
            ->orderBy('u.requestDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function updateBill($id,$bill)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.sum', '?1')
            ->setParameter(1, $bill)
            ->where('u.id = ?2')
            ->setParameter(2, $id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
    public function updatePrice($id,$sum)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.sum', '?1')
            ->setParameter(1, $sum)
            ->where('u.id = ?2')
            ->setParameter(2, $id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
    public function updateImage($id,$paht)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.preview', '?1')
            ->setParameter(1, $paht)
            ->where('u.id = ?2')
            ->setParameter(2, $id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
    public function findById($id)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult();
    }
    public function editBillPayment($id,$paht)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.paid', '?1')
            ->setParameter(1, $paht)
            ->where('u.id = ?2')
            ->setParameter(2, $id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

}