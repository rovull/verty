<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method getAllToken()
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }



    public function findOneUser($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllUserCan()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles = :val')
            ->setParameter('val', 'candidate')
            ->orderBy('u.registerDate', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function getUsersEmail()
    {
        return $this->createQueryBuilder('u')
            ->Select('u.email')
            ->andWhere('u.roles = :val')
            ->setParameter('val', 'user')
            ->distinct()
            ->getQuery()
            ->getResult()
            ;
    }

    public function updateRole($id)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.roles', '?1')
            ->setParameter(1, 'user')
            ->where('u.id = ?2')
            ->setParameter(2, $id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function updateUserEmail($id,$email)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.email', '?1')
            ->setParameter(1, $email)
            ->where('u.id = ?11')
            ->setParameter(11, $id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function updateUserPass($id,$pass)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.password', '?1')
            ->setParameter(1, $pass)
            ->where('u.id = ?11')
            ->setParameter(11, $id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
    public function fogotUserPass($email,$pass)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.password', '?1')
            ->setParameter(1, $pass)
            ->where('u.email = ?11')
            ->setParameter(11, $email)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function updateUser($id,$email,$name,$surname,$legalForm,$place,$company,$street,$postcodeCity,$phone,$undertakerID)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.email', '?1')
            ->setParameter(1, $email)
            ->set('u.name', '?2')
            ->setParameter(2, $name)
            ->set('u.surname', '?3')
            ->setParameter(3, $surname)
            ->set('u.legalForm', '?4')
            ->setParameter(4, $legalForm)
            ->set('u.place', '?5')
            ->setParameter(5, $place)
            ->set('u.company', '?6')
            ->setParameter(6, $company)
            ->set('u.street', '?7')
            ->setParameter(7, $street)
            ->set('u.postcodeCity', '?8')
            ->setParameter(8, $postcodeCity)
            ->set('u.phone', '?9')
            ->setParameter(9, $phone)
            ->set('u.undertakerID', '?10')
            ->setParameter(10, $undertakerID)
            ->where('u.id = ?11')
            ->setParameter(11, $id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function denyCandidate($id)
    {
        return $this->createQueryBuilder('u')
            ->delete()
            ->where('u.email = ?2')
            ->setParameter(2, $id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findUsers()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles = ?2')
            ->setParameter(2, 'user')
            ->orderBy('u.surname', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findDeactiveUsers()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles = ?2')
            ->setParameter(2, 'user')
            ->andWhere('u.deactivate = ?3')
            ->setParameter(3, 1)
            ->orderBy('u.surname', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findActiveUsers()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles = ?2')
            ->setParameter(2, 'user')
            ->andWhere('u.deactivate = ?3')
            ->setParameter(3, 0)
            ->orderBy('u.surname', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function upgradeUser(User $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setName($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }
    public function deactivateUser($id,$status)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.deactivate', '?1')
            ->setParameter(1, $status)
            ->where('u.id = ?2')
            ->setParameter(2, $id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
    public function checkIsAdmin($id)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = ?2')
            ->setParameter(2, $id)
            ->andWhere('u.roles = ?3')
            ->setParameter(3, 'admin')
            ->getQuery()
            ->getResult()
            ;
    }

}
