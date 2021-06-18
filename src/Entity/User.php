<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
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
    public $name;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $surname;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $legalForm;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $place;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $company;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $street;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $postcodeCity;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $phone;
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    public $undertakerID;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    public $email;


    /**
     * @ORM\Column(type="string")
     */
    public $roles ;
    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    public $password ;
    /**
     * @ORM\Column(type="string")
     */
    public $requestDate;
    /**
     * @ORM\Column(type="boolean")
     */
    public $deactivate;
    /**
     * @ORM\Column(type="string")
     */
    public $registerDate;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles()
    {
        return [$this->roles];
    }

    public function setRoles($roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street): void
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getPostcodeCity()
    {
        return $this->postcodeCity;
    }

    /**
     * @param mixed $postcodeCity
     */
    public function setPostcodeCity($postcodeCity): void
    {
        $this->postcodeCity = $postcodeCity;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getUndertakerID()
    {
        return $this->undertakerID;
    }

    /**
     * @param mixed $undertakerID
     */
    public function setUndertakerID($undertakerID): void
    {
        $this->undertakerID = $undertakerID;
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
    public function getLegalForm()
    {
        return $this->legalForm;
    }

    /**
     * @param mixed $legalForm
     */
    public function setLegalForm($legalForm): void
    {
        $this->legalForm = $legalForm;
    }

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     */
    public function setPlace($place): void
    {
        $this->place = $place;
    }

    /**
     * @return mixed
     */
    public function getActivityStatus()
    {
        return $this->activityStatus;
    }

    /**
     * @param mixed $activityStatus
     */
    public function setActivityStatus($activityStatus): void
    {
        $this->activityStatus = $activityStatus;
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
        $value=date('Y-m-d');
        $this->requestDate = $value;
    }

    /**
     * @return mixed
     */
    public function getDeactivate()
    {
        return $this->deactivate;
    }

    /**
     * @param mixed $deactivate
     */
    public function setDeactivate($deactivate): void
    {
        $this->deactivate = $deactivate;
    }

    /**
     * @param string $registerData
     * @return mixed
     */
    public function getRegisterDate(string $registerData)
    {
        return $this->registerDate;
    }

    /**
     * @param mixed $registerDate
     */
    public function setRegisterDate($registerDate): void
    {
        $this->registerDate = $registerDate;
    }


}
