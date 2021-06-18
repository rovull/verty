<?php

namespace App\Repository;

use App\Entity\MetallColor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MetallColor|null find($id, $lockMode = null, $lockVersion = null)
 * @method MetallColor|null findOneBy(array $criteria, array $orderBy = null)
 * @method MetallColor[]    findAll()
 * @method MetallColor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetallColorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MetallColor::class);
    }
    public function getAllColor()
    {

        $conn = $this->getEntityManager()
            ->getConnection();
        $sql='SELECT * FROM metall_color        ';

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }

        return $stmt->fetchAll();
    }

    // /**
    //  * @return MetallColor[] Returns an array of MetallColor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MetallColor
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
