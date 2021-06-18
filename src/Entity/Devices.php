<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\DevicesRepository")
 */

class Devices
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
    public $tokenDevice;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $setting;
    /**
     * @ORM\Column(type="string", length=180)
     */
    public $type;

    /**
     * @return mixed
     */
    public function getTokenDevice()
    {
        return $this->tokenDevice;
    }

    /**
     * @param mixed $tokenDevice
     */
    public function setTokenDevice($tokenDevice): void
    {
        $this->tokenDevice = $tokenDevice;
    }

    /**
     * @return mixed
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * @param mixed $setting
     */
    public function setSetting($setting): void
    {
        $this->setting = $setting;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

}