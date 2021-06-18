<?php

namespace App\Repository;

use App\Entity\Metall;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Metall|null find($id, $lockMode = null, $lockVersion = null)
 * @method Metall|null findOneBy(array $criteria, array $orderBy = null)
 * @method Metall[]    findAll()
 * @method Metall[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetallRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Metall::class);
    }
    public function getAllBlack()
    {

        $conn = $this->getEntityManager()
            ->getConnection();
        $sql='SELECT * FROM metall ';

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function setAllTokenNow()
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
    public function dell()
    {
        return $this->createQueryBuilder('u')
            ->delete()
            ;

    }


        // /**
    //  * @return MetallController[] Returns an array of MetallController objects
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
    public function findOneBySomeField($value): ?MetallController
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
