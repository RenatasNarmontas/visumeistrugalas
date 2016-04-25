<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * Forecast
 *
 * @ORM\Table(name="forecasts", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"forecast_date", "city_id", "forecast_days", "provider_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ForecastRepository")
 */
class Forecast
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
     * @var Provider
     *
     * @ManyToOne(targetEntity="Provider")
     * @JoinColumn(name="provider_id", referencedColumnName="id")
     *
     */
    private $provider;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="forecast_date", type="date")
     */
    private $forecastDate;

    /**
     * @var int
     *
     * @ORM\Column(name="forecast_days", type="integer")
     */
    private $forecastDays;

    /**
     * @var City
     *
     * @ManyToOne(targetEntity="City")
     * @JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;

    /**
     * @var float
     *
     * @ORM\Column(name="temperature_high", type="float", nullable=true)
     */
    private $temperatureHigh;

    /**
     * @var float
     *
     * @ORM\Column(name="temperature_high_deviation", type="float", nullable=true)
     */
    private $temperatureHighDeviation;

    /**
     * @var float
     *
     * @ORM\Column(name="temperature_low", type="float", nullable=true)
     */
    private $temperatureLow;

    /**
     * @var float
     *
     * @ORM\Column(name="temperature_low_deviation", type="float", nullable=true)
     */
    private $temperatureLowDeviation;

    /**
     * @var float
     *
     * @ORM\Column(name="humidity", type="float", nullable=true)
     */
    private $humidity;

    /**
     * @var float
     *
     * @ORM\Column(name="humidity_deviation", type="float", nullable=true)
     */
    private $humidityDeviation;

    /**
     * @var float
     *
     * @ORM\Column(name="pressure", type="float", nullable=true)
     */
    private $pressure;

    /**
     * @var float
     *
     * @ORM\Column(name="pressure_deviation", type="float", nullable=true)
     */
    private $pressureDeviation;


    /**
     * Get id
     *
     * @return Provider
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set provider
     *
     * @param Provider $provider
     *
     * @return Forecast
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return int
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set forecastDate
     *
     * @param \DateTime $forecastDate
     *
     * @return Forecast
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
     * Set city
     *
     * @param City $city
     *
     * @return Forecast
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set temperatureHigh
     *
     * @param float $temperatureHigh
     *
     * @return Forecast
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
     * @return Forecast
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
     * Set forecastDays
     *
     * @param integer $forecastDays
     *
     * @return Forecast
     */
    public function setForecastDays($forecastDays)
    {
        $this->forecastDays = $forecastDays;

        return $this;
    }

    /**
     * Get forecastDays
     *
     * @return integer
     */
    public function getForecastDays()
    {
        return $this->forecastDays;
    }

    /**
     * Set temperatureHighDeviation
     *
     * @param float $temperatureHighDeviation
     *
     * @return Forecast
     */
    public function setTemperatureHighDeviation($temperatureHighDeviation)
    {
        $this->temperatureHighDeviation = $temperatureHighDeviation;

        return $this;
    }

    /**
     * Get temperatureHighDeviation
     *
     * @return float
     */
    public function getTemperatureHighDeviation()
    {
        return $this->temperatureHighDeviation;
    }

    /**
     * Set temperatureLowDeviation
     *
     * @param float $temperatureLowDeviation
     *
     * @return Forecast
     */
    public function setTemperatureLowDeviation($temperatureLowDeviation)
    {
        $this->temperatureLowDeviation = $temperatureLowDeviation;

        return $this;
    }

    /**
     * Get temperatureLowDeviation
     *
     * @return float
     */
    public function getTemperatureLowDeviation()
    {
        return $this->temperatureLowDeviation;
    }

    /**
     * Set humidity
     *
     * @param float $humidity
     *
     * @return Forecast
     */
    public function setHumidity($humidity)
    {
        $this->humidity = $humidity;

        return $this;
    }

    /**
     * Get humidity
     *
     * @return float
     */
    public function getHumidity()
    {
        return $this->humidity;
    }

    /**
     * Set humidityDeviation
     *
     * @param float $humidityDeviation
     *
     * @return Forecast
     */
    public function setHumidityDeviation($humidityDeviation)
    {
        $this->humidityDeviation = $humidityDeviation;

        return $this;
    }

    /**
     * Get humidityDeviation
     *
     * @return float
     */
    public function getHumidityDeviation()
    {
        return $this->humidityDeviation;
    }

    /**
     * Set pressure
     *
     * @param float $pressure
     *
     * @return Forecast
     */
    public function setPressure($pressure)
    {
        $this->pressure = $pressure;

        return $this;
    }

    /**
     * Get pressure
     *
     * @return float
     */
    public function getPressure()
    {
        return $this->pressure;
    }

    /**
     * Set pressureDeviation
     *
     * @param float $pressureDeviation
     *
     * @return Forecast
     */
    public function setPressureDeviation($pressureDeviation)
    {
        $this->pressureDeviation = $pressureDeviation;

        return $this;
    }

    /**
     * Get pressureDeviation
     *
     * @return float
     */
    public function getPressureDeviation()
    {
        return $this->pressureDeviation;
    }
}
