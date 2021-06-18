<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnouncementRepository")
 */

class Announcement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public $id;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $appeal;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $title;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $name;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $surname;
    /**
     * @ORM\Column(type="string", length=180)
     *
     */
    public $releaseDate;
    /**
     * @ORM\Column(type="string", length=180)
     *
     */
    public $birthDate;
    /**
     * @ORM\Column(type="string", length=180)
     *
     */
    public $deathDate;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $cemeteryAddress;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $emailAddresses;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $pdfFile;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $userID;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $picture;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $textToSpeak;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $city;
    /**
     * @ORM\Column(type="boolean", length=180)
     */
    public $status;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $ceremonyDate;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $ceremonyTime;



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAppeal()
    {
        return $this->appeal;
    }

    /**
     * @param mixed $appeal
     */
    public function setAppeal($appeal): void
    {
        $this->appeal = $appeal;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * @param mixed $releaseDate
     */
    public function setReleaseDate($releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param mixed $birthDate
     */
    public function setBirthDate($birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return mixed
     */
    public function getDeathDate()
    {
        return $this->deathDate;
    }

    /**
     * @param mixed $deathDate
     */
    public function setDeathDate($deathDate): void
    {
        $this->deathDate = $deathDate;
    }

    /**
     * @return mixed
     */
    public function getCemeteryAddress()
    {
        return $this->cemeteryAddress;
    }

    /**
     * @param mixed $cemeteryAddress
     */
    public function setCemeteryAddress($cemeteryAddress): void
    {
        $this->cemeteryAddress = $cemeteryAddress;
    }

    /**
     * @return mixed
     */
    public function getEmailAddresses()
    {
        return $this->emailAddresses;
    }

    /**
     * @param mixed $emailAddresses
     */
    public function setEmailAddresses($emailAddresses): void
    {
        $this->emailAddresses = $emailAddresses;
    }

    /**
     * @return mixed
     */
    public function getPdfFile()
    {
        return $this->pdfFile;
    }

    /**
     * @param mixed $pdfFile
     */
    public function setPdfFile($pdfFile): void
    {
        $this->pdfFile = $pdfFile;
    }



    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @param mixed $userID
     */
    public function setUserID($userID): void
    {
        $this->userID = $userID;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture): void
    {
        $this->picture = $picture;
    }

    /**
     * @return mixed
     */
    public function getTextToSpeak()
    {
        return $this->textToSpeak;
    }

    /**
     * @param mixed $textToSpeak
     */
    public function setTextToSpeak($textToSpeak): void
    {
        $this->textToSpeak = $textToSpeak;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getCeremonyDate()
    {
        return $this->ceremonyDate;
    }

    /**
     * @param mixed $ceremonyDate
     */
    public function setCeremonyDate($ceremonyDate): void
    {
        $this->ceremonyDate = $ceremonyDate;
    }

    /**
     * @return mixed
     */
    public function getCeremonyTime()
    {
        return $this->ceremonyTime;
    }

    /**
     * @param mixed $ceremonyTime
     */
    public function setCeremonyTime($ceremonyTime): void
    {
        $this->ceremonyTime = $ceremonyTime;
    }


}