<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * City
 *
 * @ORM\Table(name="cities")
 * @ORM\Entity
 **/

class City
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="country_iso3166", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $countryIso3166;

    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     * @return City
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set country.
     *
     * @param string $country
     * @return City
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Get country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set countryIso3166
     *
     * @param string $countryIso3166
     *
     * @return City
     */
    public function setCountryIso3166($countryIso3166)
    {
        $this->countryIso3166 = $countryIso3166;

        return $this;
    }

    /**
     * Get countryIso3166
     *
     * @return string
     */
    public function getCountryIso3166()
    {
        return $this->countryIso3166;
    }
}
