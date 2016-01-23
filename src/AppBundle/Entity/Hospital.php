<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hospital
 *
 * @ORM\Table(name="hospital")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HospitalRepository")
 */
class Hospital
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="shortName", type="string", length=255, unique=true)
     */
    private $shortName;

    /**
     * @var string
     *
     * @ORM\Column(name="fullName", type="string", length=255)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable = true)
     */
    private $address;
    /**
     * @var string
     *
     * @ORM\Column(name="ka_region", type="text", nullable=true)
     */
    private $ka_region;
    /**
     * @var string
     *
     * @ORM\Column(name="ka_district", type="text",nullable=true)
     */
    private $ka_district;
    /**
     * @var string
     *
     * @ORM\Column(name="ka_city", type="text", nullable = true)
     */
    private $ka_city;
    /**
     * @var string
     *
     * @ORM\Column(name="ka_street", type="text", nullable = true)
     */
    private $ka_street;
    /**
     * @var string
     *
     * @ORM\Column(name="ka_building", type="text", nullable = true)
     */
    private $ka_building;
    /**
     * @ORM\ManyToOne(targetEntity="Territory")
     */
    private $territory;
    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="text", nullable = true)
     */
    private $latitude;
    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="text", nullable = true)
     */
    private $longitude;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set shortName
     *
     * @param string $shortName
     * @return Hospital
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * Get shortName
     *
     * @return string 
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     * @return Hospital
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Hospital
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set territory
     *
     * @param string $territory
     * @return Hospital
     */
    public function setTerritory($territory)
    {
        $this->territory = $territory;

        return $this;
    }

    /**
     * Get territory
     *
     * @return string 
     */
    public function getTerritory()
    {
        return $this->territory;
    }

    /**
     * Set ka_region
     *
     * @param string $kaRegion
     * @return Hospital
     */
    public function setKaRegion($kaRegion)
    {
        $this->ka_region = $kaRegion;

        return $this;
    }

    /**
     * Get ka_region
     *
     * @return string 
     */
    public function getKaRegion()
    {
        return $this->ka_region;
    }

    /**
     * Set ka_district
     *
     * @param string $kaDistrict
     * @return Hospital
     */
    public function setKaDistrict($kaDistrict)
    {
        $this->ka_district = $kaDistrict;

        return $this;
    }

    /**
     * Get ka_district
     *
     * @return string 
     */
    public function getKaDistrict()
    {
        return $this->ka_district;
    }

    /**
     * Set ka_city
     *
     * @param string $kaCity
     * @return Hospital
     */
    public function setKaCity($kaCity)
    {
        $this->ka_city = $kaCity;

        return $this;
    }

    /**
     * Get ka_city
     *
     * @return string 
     */
    public function getKaCity()
    {
        return $this->ka_city;
    }

    /**
     * Set ka_street
     *
     * @param string $kaStreet
     * @return Hospital
     */
    public function setKaStreet($kaStreet)
    {
        $this->ka_street = $kaStreet;

        return $this;
    }

    /**
     * Get ka_street
     *
     * @return string 
     */
    public function getKaStreet()
    {
        return $this->ka_street;
    }

    /**
     * Set ka_building
     *
     * @param string $kaBuilding
     * @return Hospital
     */
    public function setKaBuilding($kaBuilding)
    {
        $this->ka_building = $kaBuilding;

        return $this;
    }

    /**
     * Get ka_building
     *
     * @return string 
     */
    public function getKaBuilding()
    {
        return $this->ka_building;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Hospital
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Hospital
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}
