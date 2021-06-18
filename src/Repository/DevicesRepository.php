<?php


namespace App\Repository;
use App\Entity\Devices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

class DevicesRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Devices::class);
    }

    public function getSetting($token,$setting)
    {
        return $this->createQueryBuilder('u')
            ->set('u.setting', '?1')
            ->setParameter(1, $setting)
            ->andWhere('u.tokenDevice = :val')
            ->setParameter('val', $token)
            ->getQuery()
            ->getResult();
    }
    public function getAllToken()
    {
        return $this->createQueryBuilder('u')
            ->Select('u.tokenDevice')
            ->distinct()
            ->getQuery()
            ->getResult();
    }
    public function getAllTokenDay()
    {
        return $this->createQueryBuilder('u')
            ->Select('u.tokenDevice')
            ->andWhere('u.setting = :val')
            ->setParameter('val', 1)
            ->distinct()
            ->getQuery()
            ->getResult();
    }
    public function getAllTokenNow()
    {
        return $this->createQueryBuilder('u')
            ->Select('u.tokenDevice')
            ->andWhere('u.setting = :val')
            ->setParameter('val', 3)
            ->distinct()
            ->getQuery()
            ->getResult();
    }
    public function getAllTokenWeek()
    {
        return $this->createQueryBuilder('u')
            ->Select('u.tokenDevice')
            ->andWhere('u.setting = :val')
            ->setParameter('val', 2)
            ->distinct()
            ->getQuery()
            ->getResult();
    }

}