<?php

namespace App\Repository;

use App\Entity\Policy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Policy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Policy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Policy[]    findAll()
 * @method Policy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PolicyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Policy::class);
    }

    public function findPolicy()
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql='SELECT * FROM policy ORDER BY id DESC LIMIT 1';

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }


}
