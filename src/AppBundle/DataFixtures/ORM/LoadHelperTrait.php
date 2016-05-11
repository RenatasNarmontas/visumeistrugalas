<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 11/05/16
 * Time: 19:26
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\City;
use AppBundle\Entity\Provider;

trait LoadHelperTrait
{
    abstract public function getReference($name);

    /**
     * Returns City reference
     * @param string $cityId
     * @return City
     */
    private function getCity(string $cityId): City
    {
        switch ($cityId) {
            case '1':
                $city = $this->getReference('Vilnius');
                break;
            case '2':
                $city = $this->getReference('Paris');
                break;
            case '3':
                $city = $this->getReference('Berlin');
                break;
            case '4':
                $city = $this->getReference('London');
                break;
            default:
                $city = $this->getReference('Vilnius');
                break;
        }

        return $city;
    }

    /**
     * Returns Provider reference
     * @param string $providerId
     * @return Provider
     */
    private function getProvider(string $providerId): Provider
    {
        switch ($providerId) {
            case '1':
                $provider = $this->getReference('OpenWeatherMap');
                break;
            case '2':
                $provider = $this->getReference('Yahoo');
                break;
            case '3':
                $provider = $this->getReference('Weather Underground');
                break;
            case '4':
                $provider = $this->getReference('Advanced Weather');
                break;
            default:
                $provider = $this->getReference('OpenWeatherMap');
                break;
        }

        return $provider;
    }
}
