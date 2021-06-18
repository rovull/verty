<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\InvoicesRepository")
 */

class Invoices
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
    public $invoiceId;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $company;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $name;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $requestDate;
    /**
     * @ORM\Column(type="integer", length=180)
     */
    public $sum;
    /**
     * @ORM\Column(type="boolean", length=180)
     */
    public $paid;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $pdf;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $userId;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $preview;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $surname;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getInvoiceId()
    {
        return $this->invoiceId;
    }

    /**
     * @param mixed $invoiceId
     */
    public function setInvoiceId($invoiceId): void
    {
        $this->invoiceId = $invoiceId;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company): void
    {
        $this->company = $company;
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
    public function getRequestDate()
    {
        return $this->requestDate;
    }

    /**
     * @param mixed $requestDate
     */
    public function setRequestDate($requestDate): void
    {
        $this->requestDate = $requestDate;
    }

    /**
     * @return mixed
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * @param mixed $sum
     */
    public function setSum($sum): void
    {
        $this->sum = $sum;
    }

    /**
     * @return mixed
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * @param mixed $paid
     */
    public function setPaid($paid): void
    {
        $this->paid = $paid;
    }

    /**
     * @return mixed
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * @param mixed $pdf
     */
    public function setPdf($pdf): void
    {
        $this->pdf = $pdf;
    }

    /**
     * @return mixed
     */
    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * @param mixed $preview
     */
    public function setPreview($preview): void
    {
        $this->preview = $preview;
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

}