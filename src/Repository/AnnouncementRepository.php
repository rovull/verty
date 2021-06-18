<?php


namespace App\Repository;

use App\Entity\Announcement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;


class AnnouncementRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Announcement::class);
    }

    public function findAllpubl($value2)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.status = 1')
            ->andWhere('u.releaseDate <= :val3')
            ->setParameter('val3', $value2)
            ->getQuery()
            ->getResult();
    }
    public function findById($value)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = ' SELECT announcement.id, announcement.title, announcement.release_date, announcement.birth_date, announcement.death_date, announcement.pdf_file,announcement.ceremony_date,announcement.ceremony_time, announcement.picture, announcement.user_id, announcement.text_to_speak, announcement.city, announcement.status, announcement.appeal, announcement.email_addresses , announcement.name, announcement.surname ,announcement.cemetery_address FROM announcement WHERE id=' . $value . '  ';
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAllById($value, $value2)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.userID = :val')
            ->setParameter('val', $value)
            ->andWhere('YEAR(u.releaseDate) = :val3')
            ->setParameter('val3', $value2)
            ->orderBy('u.releaseDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findFuture($appeal)
    {
        $value = date('Y-m-d');
        return $this->createQueryBuilder('u')
            ->andWhere('u.releaseDate > :val')
            ->setParameter('val', $value)
            ->andWhere('u.userID = :val1')
            ->setParameter('val1', $appeal)
            ->getQuery()
            ->getResult();
    }

    public function findPublich($value2)
    {
        $value = date('Y-m-d');
        return $this->createQueryBuilder('u')
            ->Select('YEAR(u.releaseDate)')
            ->andWhere('u.releaseDate <= :val')
            ->setParameter('val', $value)
            ->andWhere('u.userID = :val3')
            ->setParameter('val3', $value2)
            ->distinct()
            ->orderBy('u.releaseDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findPublichDay()
    {
        $value = date('Y-m-d');

         $value2=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $value) ) ));

        return $this->createQueryBuilder('u')
            ->andWhere('u.releaseDate = :val')
            ->setParameter('val', $value2)
            ->distinct()
            ->orderBy('u.releaseDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findPublichWeek()
    {
        $value = date('Y-m-d');
        $value2=date('Y-m-d',(strtotime ( '-7 day' , strtotime ( $value) ) ));
        $value=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $value) ) ));

        return $this->createQueryBuilder('u')
            ->andWhere('u.releaseDate BETWEEN :from AND :to')
            ->setParameter('from',$value2)
            ->setParameter('to', $value)
            ->distinct()
            ->orderBy('u.releaseDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findPublichMon($id)
    {
        $value = date('Y-m-d');
        $value2=date('Y-m-d',(strtotime ( '-4 week' , strtotime ( $value) ) ));
        $value=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $value) ) ));

        return $this->createQueryBuilder('u')
            ->andWhere('u.releaseDate BETWEEN :from AND :to')
            ->setParameter('from',$value2)
            ->setParameter('to', $value)
            ->andWhere('u.userID= :val')
            ->setParameter('val', $id)
            ->distinct()
            ->getQuery()
            ->getResult();
    }
    public function findPublichMonUp($id,$value)
    {
        $value2=date('Y-m-d',(strtotime ( '-4 week' , strtotime ( $value) ) ));
        $value=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $value) ) ));

        return $this->createQueryBuilder('u')
            ->andWhere('u.releaseDate BETWEEN :from AND :to')
            ->setParameter('from',$value2)
            ->setParameter('to', $value)
            ->andWhere('u.userID= :val')
            ->setParameter('val', $id)
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    public function findYearPublich($value1, $value2)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('YEAR(u.releaseDate)=:val1')
            ->setParameter('val1', $value2)
            ->andWhere('u.userID = :val2')
            ->setParameter('val2', $value1)
            ->getQuery()
            ->getResult();
    }

    public function getAllYearsAnnouncementAdmin()
    {
        return $this->createQueryBuilder('u')
            ->Select('YEAR(u.releaseDate)')
            ->getQuery()
            ->getResult();
    }

    public function getAllAnnouncementAdmin($year)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = ' SELECT announcement.id,user.name AS publisher_name, user.surname AS publisher_surname,  announcement.title, announcement.release_date, announcement.birth_date, announcement.death_date, announcement.pdf_file,announcement.ceremony_date,announcement.ceremony_time, announcement.picture, announcement.user_id, announcement.text_to_speak, announcement.city, announcement.status, announcement.appeal, announcement.email_addresses , announcement.name, announcement.surname ,announcement.cemetery_address FROM announcement LEFT JOIN user ON announcement.user_id = user.id WHERE YEAR(announcement.release_date)=' . $year . '  ';
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();



    }

    public function findCity()
    {
        return $this->createQueryBuilder('u')
            ->Select('u.city')
            ->groupBy('u.city')
            ->getQuery()
            ->getResult();
    }

    public function findFilterDate($value1, $value2)
    {
        $value = date('Y-m-d');
        $value4 = 0;
        
        return $this->createQueryBuilder('u')
            ->andWhere('u.deathDate >= :val')
            ->setParameter('val', $value1)
            ->andWhere('u.deathDate <= :val2')
            ->setParameter('val2', $value2)
            ->andWhere('u.releaseDate <= :val3')
            ->setParameter('val3', $value)
            ->andWhere('u.status != :val1')
            ->setParameter('val1', $value4)
            ->getQuery()
            ->getResult();
    }

    public function findFilterDateCity($value1, $value2, $city)
    {
        $value = date('Y-m-d');
        $value4 = 0;
        return $this->createQueryBuilder('u')
            ->andWhere('u.deathDate >= :val')
            ->setParameter('val', $value1)
            ->andWhere('u.deathDate <= :val2')
            ->setParameter('val2', $value2)
            ->andWhere('u.releaseDate <= :val4')
            ->setParameter('val4', $value)
            ->andWhere('u.status != :val1')
            ->setParameter('val1', $value4)
            ->getQuery()
            ->getResult();
    }

    public function findFilterDateName($value1, $value2, $name)
    {
        $value = date('Y-m-d');
        $value4 = 0;
        return $this->createQueryBuilder('u')
            ->andWhere('u.name LIKE  :name')
            ->orWhere('u.surname LIKE :name')
            ->setParameter('name', $name.'%')
            ->andWhere('u.deathDate >= :val')
            ->setParameter('val', $value1)
            ->andWhere('u.deathDate <= :val2')
            ->setParameter('val2', $value2)
            ->andWhere('u.releaseDate <= :val3')
            ->setParameter('val3', $value)
            ->andWhere('u.status != :val1')
            ->setParameter('val1', $value4)
            ->getQuery()
            ->getResult();
    }
    public function findFilterDateName1($value1, $value2, $name,$appeal5)
    {
        $value = date('Y-m-d');
        $value4 = 0;
        return $this->createQueryBuilder('u')
            ->andWhere('u.name LIKE  :name')
            ->orWhere('u.surname LIKE :name')
            ->setParameter('name', $name.'%')
            ->orWhere('u.surname IN (:name1)')
            ->setParameter('name1', $appeal5[1])
            ->orWhere('u.name IN (:name2)')
            ->setParameter('name2', $appeal5[0])
            ->andWhere('u.deathDate >= :val')
            ->setParameter('val', $value1)
            ->andWhere('u.deathDate <= :val2')
            ->setParameter('val2', $value2)
            ->andWhere('u.releaseDate <= :val3')
            ->setParameter('val3', $value)
            ->andWhere('u.status != :val1')
            ->setParameter('val1', $value4)
            ->getQuery()
            ->getResult();
    }

    public function findFilterDateCityName($value1, $value2,$city, $name)
    {
        $value = date('Y-m-d');
        $value4 = 0;
        return $this->createQueryBuilder('u')
            ->andWhere('u.name LIKE :name')
            ->orWhere('u.surname LIKE :name')
            ->setParameter('name', $name.'%')
            ->andWhere('u.deathDate >= :val')
            ->setParameter('val', $value1)
            ->andWhere('u.deathDate <= :val2')
            ->setParameter('val2', $value2)
            ->andWhere('u.city = :city')
            ->setParameter('city', $city)
            ->andWhere('u.releaseDate <= :val3')
            ->setParameter('val3', $value)
            ->andWhere('u.status != :val1')
            ->setParameter('val1', $value4)
            ->getQuery()
            ->getResult();
    }
    public function findFilterDateCityName1($value1, $value2,$city, $name,$appeal5)
    {
        $value = date('Y-m-d');
        $value4 = 0;
        return $this->createQueryBuilder('u')
            ->andWhere('u.name LIKE :name')
            ->orWhere('u.surname LIKE :name')
            ->setParameter('name', $name.'%')
            ->orWhere('u.surname IN (:name1)')
            ->setParameter('name1', $appeal5[1])
            ->orWhere('u.name IN (:name2)')
            ->setParameter('name2', $appeal5[0])
            ->andWhere('u.deathDate >= :val')
            ->setParameter('val', $value1)
            ->andWhere('u.deathDate <= :val2')
            ->setParameter('val2', $value2)
            ->andWhere('u.city = :city')
            ->setParameter('city', $city)
            ->andWhere('u.releaseDate <= :val3')
            ->setParameter('val3', $value)
            ->andWhere('u.status != :val1')
            ->setParameter('val1', $value4)
            ->getQuery()
            ->getResult();
    }
    public function findFilterName($name)
    {
        $value1 = 0;
        $value = date('Y-m-d');
        return $this->createQueryBuilder('u')
            ->andWhere('u.name LIKE  :name')
            ->orWhere('u.surname LIKE :name')
            ->setParameter('name', $name.'%')
            ->andWhere('u.releaseDate <= :val3')
            ->setParameter('val3', $value)
            ->andWhere('u.status != :val1')
            ->setParameter('val1', $value1)
            ->getQuery()
            ->getResult();
    }
    public function findFilterName1($name,$appeal5)
    {
        $value1 = 0;
        $value = date('Y-m-d');
        return $this->createQueryBuilder('u')
            ->andWhere('u.name LIKE  :name')
            ->orWhere('u.surname LIKE :name')
            ->setParameter('name', $name.'%')
            ->orWhere('u.surname IN (:name1)')
            ->setParameter('name1', $appeal5[1])
            ->orWhere('u.name IN (:name2)')
            ->setParameter('name2', $appeal5[0])
            ->andWhere('u.releaseDate <= :val3')
            ->setParameter('val3', $value)
            ->andWhere('u.status != :val1')
            ->setParameter('val1', $value1)
            ->getQuery()
            ->getResult();
    }

    public function findFilterCity($city)
    {
        $value = date('Y-m-d');
        $value1 = 0;
        return $this->createQueryBuilder('u')
            ->andWhere('u.city = :val3')
            ->setParameter('val3', $city)
            ->andWhere('u.releaseDate <= :val')
            ->setParameter('val', $value)
            ->andWhere('u.status != :val1')
            ->setParameter('val1', $value1)
            ->getQuery()
            ->getResult();
    }

    public function findFilterCityName($city, $name)
    {
        $value = date('Y-m-d');
        $value1 = 0;
        return $this->createQueryBuilder('u')
            ->andWhere('u.name LIKE :name')
            ->orWhere('u.surname LIKE :name')
            ->setParameter('name', $name.'%')
            ->andWhere('u.city = :val3')
            ->setParameter('val3', $city)
            ->andWhere('u.releaseDate <= :val')
            ->setParameter('val', $value)
            ->andWhere('u.status != :val1')
            ->setParameter('val1', $value1)
            ->getQuery()
            ->getResult();
    }
    public function findFilterCityName1($city, $name,$appeal5)
    {
        $value = date('Y-m-d');
        $value1 = 0;
        return $this->createQueryBuilder('u')
            ->andWhere('u.name LIKE :name')
            ->orWhere('u.surname LIKE :name')
            ->setParameter('name', $name.'%')
            ->orWhere('u.surname IN (:name1)')
            ->setParameter('name1', $appeal5[1])
            ->orWhere('u.name IN (:name2)')
            ->setParameter('name2', $appeal5[0])
            ->andWhere('u.city = :val3')
            ->setParameter('val3', $city)
            ->andWhere('u.releaseDate <= :val')
            ->setParameter('val', $value)
            ->andWhere('u.status != :val1')
            ->setParameter('val1', $value1)
            ->getQuery()
            ->getResult();
    }

    public function findBillById($id, $sum)
    {
        return $this->createQueryBuilder('u')
            ->where('MONTH(u.releaseDate) = MONTH(:val)')
            ->setParameter('val', $sum)
            ->andWhere('u.userID = :val3')
            ->setParameter('val3', $id)
            ->getQuery()
            ->getResult();
    }

    public function findYearsById($id)
    {
        return $this->createQueryBuilder('u')
            ->Select('YEAR(u.releaseDate)')
            ->where('u.userID =:val')
            ->setParameter('val', $id)
            ->orderBy('u.releaseDate', 'ASC')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    public function findYears()
    {
        return $this->createQueryBuilder('u')
            ->Select('YEAR(u.releaseDate)')
            ->orderBy('u.releaseDate', 'ASC')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    public function deleteUserAn($id)
    {
        return $this->createQueryBuilder('u')
            ->delete()
            ->where('u.userID = ?2')
            ->setParameter(2, $id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function updateAnnouncementwithour($id, $ceremonyDate, $appeal, $title, $name, $surname, $releaseDate, $birthDate, $deathDate, $cemeteryAddress, $emailAddresses, $city, $ceremonyTime,$textToSpeech)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.appeal', '?1')
            ->setParameter(1, $appeal)
            ->set('u.title', '?2')
            ->setParameter(2, $title)
            ->set('u.surname', '?3')
            ->setParameter(3, $surname)
            ->set('u.name', '?4')
            ->setParameter(4, $name)
            ->set('u.releaseDate', '?5')
            ->setParameter(5, $releaseDate)
            ->set('u.birthDate', '?6')
            ->setParameter(6, $birthDate)
            ->set('u.deathDate', '?7')
            ->setParameter(7, $deathDate)
            ->set('u.cemeteryAddress', '?8')
            ->setParameter(8, $cemeteryAddress)
            ->set('u.emailAddresses', '?9')
            ->setParameter(9, $emailAddresses)
            ->set('u.ceremonyDate', '?11')
            ->setParameter(11, $ceremonyDate)
            ->set('u.ceremonyTime', '?12')
            ->setParameter(12, $ceremonyTime)
            ->set('u.city', '?13')
            ->setParameter(13, $city)
            ->set('u.textToSpeak', '?14')
            ->setParameter(14, $textToSpeech)
            ->where('u.id = ?15')
            ->setParameter(15, $id)
            ->getQuery()
            ->getSingleScalarResult();

    }

    public function updateAnnouncement($id, $text, $ceremonyDate, $previewImage, $appeal, $title, $name, $surname, $releaseDate, $birthDate, $deathDate, $cemeteryAddress, $emailAddresses, $pdf_file, $city, $ceremonyTime)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.appeal', '?1')
            ->setParameter(1, $appeal)
            ->set('u.title', '?2')
            ->setParameter(2, $title)
            ->set('u.surname', '?3')
            ->setParameter(3, $surname)
            ->set('u.name', '?4')
            ->setParameter(4, $name)
            ->set('u.releaseDate', '?5')
            ->setParameter(5, $releaseDate)
            ->set('u.birthDate', '?6')
            ->setParameter(6, $birthDate)
            ->set('u.deathDate', '?7')
            ->setParameter(7, $deathDate)
            ->set('u.cemeteryAddress', '?8')
            ->setParameter(8, $cemeteryAddress)
            ->set('u.emailAddresses', '?9')
            ->setParameter(9, $emailAddresses)
            ->set('u.pdfFile', '?10')
            ->setParameter(10, $pdf_file)
            ->set('u.ceremonyDate', '?11')
            ->setParameter(11, $ceremonyDate)
            ->set('u.ceremonyTime', '?12')
            ->setParameter(12, $ceremonyTime)
            ->set('u.picture', '?13')
            ->setParameter(13, $previewImage)
            ->set('u.city', '?14')
            ->setParameter(14, $city)
            ->set('u.textToSpeak', '?15')
            ->setParameter(15, $text)
            ->where('u.id = ?16')
            ->setParameter(16, $id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function deactivateAnnouncement($id)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.status', '?1')
            ->setParameter(1, 0)
            ->where('u.id = ?2')
            ->setParameter(2, $id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function activateAnnouncement($id)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.status', '?1')
            ->setParameter(1, 1)
            ->where('u.id = ?2')
            ->setParameter(2, $id)
            ->getQuery()
            ->getSingleScalarResult();
    }
}