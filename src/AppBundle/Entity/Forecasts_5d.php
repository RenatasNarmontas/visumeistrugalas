<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Forecasts_5d
 *
 * @ORM\Table(name="forecasts_5d")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Forecasts_5dRepository")
 */
class Forecasts_5d
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
     * @var int
     *
     * @ORM\Column(name="provider_id", type="integer")
     */
    private $providerId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="forecast_date", type="date")
     */
    private $forecastDate;

    /**
     * @var int
     *
     * @ORM\Column(name="city_id", type="integer")
     */
    private $cityId;

    /**
     * @var float
     *
     * @ORM\Column(name="temperature_high", type="float")
     */
    private $temperatureHigh;

    /**
     * @var float
     *
     * @ORM\Column(name="temperature_low", type="float", nullable=true)
     */
    private $temperatureLow;

    /**
     * @var float
     *
     * @ORM\Column(name="deviation", type="float", nullable=true)
     */
    private $deviation;

    /**
     * @var int
     *
     * @ORM\Column(name="humidity", type="integer", nullable=true)
     */
    private $humidity;

    /**
     * @var int
     *
     * @ORM\Column(name="pressure", type="integer", nullable=true)
     */
    private $pressure;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set providerId
     *
     * @param integer $providerId
     *
     * @return Forecasts_5d
     */
    public function setProviderId($providerId)
    {
        $this->providerId = $providerId;

        return $this;
    }

    /**
     * Get providerId
     *
     * @return int
     */
    public function getProviderId()
    {
        return $this->providerId;
    }

    /**
     * Set forecastDate
     *
     * @param \DateTime $forecastDate
     *
     * @return Forecasts_5d
     */
    public function setForecastDate($forecastDate)
    {
        $this->forecastDate = $forecastDate;

        return $this;
    }

    /**
     * Get forecastDate
     *
     * @return \DateTime
     */
    public function getForecastDate()
    {
        return $this->forecastDate;
    }

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return Forecasts_5d
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }

    /**
     * Get cityId
     *
     * @return int
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Set temperatureHigh
     *
     * @param float $temperatureHigh
     *
     * @return Forecasts_5d
     */
    public function setTemperatureHigh($temperatureHigh)
    {
        $this->temperatureHigh = $temperatureHigh;

        return $this;
    }

    /**
     * Get temperatureHigh
     *
     * @return float
     */
    public function getTemperatureHigh()
    {
        return $this->temperatureHigh;
    }

    /**
     * Set temperatureLow
     *
     * @param float $temperatureLow
     *
     * @return Forecasts_5d
     */
    public function setTemperatureLow($temperatureLow)
    {
        $this->temperatureLow = $temperatureLow;

        return $this;
    }

    /**
     * Get temperatureLow
     *
     * @return float
     */
    public function getTemperatureLow()
    {
        return $this->temperatureLow;
    }

    /**
     * Set deviation
     *
     * @param float $deviation
     *
     * @return Forecasts_5d
     */
    public function setDeviation($deviation)
    {
        $this->deviation = $deviation;

        return $this;
    }

    /**
     * Get deviation
     *
     * @return float
     */
    public function getDeviation()
    {
        return $this->deviation;
    }

    /**
     * Set humidity
     *
     * @param integer $humidity
     *
     * @return Forecasts_5d
     */
    public function setHumidity($humidity)
    {
        $this->humidity = $humidity;

        return $this;
    }

    /**
     * Get humidity
     *
     * @return int
     */
    public function getHumidity()
    {
        return $this->humidity;
    }

    /**
     * Set pressure
     *
     * @param integer $pressure
     *
     * @return Forecasts_5d
     */
    public function setPressure($pressure)
    {
        $this->pressure = $pressure;

        return $this;
    }

    /**
     * Get pressure
     *
     * @return int
     */
    public function getPressure()
    {
        return $this->pressure;
    }
}
