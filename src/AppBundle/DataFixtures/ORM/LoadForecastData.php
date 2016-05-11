<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 11/05/16
 * Time: 17:26
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\City;
use AppBundle\Entity\Forecast;
use AppBundle\Entity\Provider;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * Class LoadForecastData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadForecastData extends AbstractFixture implements OrderedFixtureInterface
{
    use LoadHelperTrait;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $fileName = __DIR__.'/forecasts.csv';
        $csv = array_map('str_getcsv', file($fileName));

        foreach ($csv as $data) {
            $forecasts = new Forecast();

            /** @var Provider $provider */
            $provider = $this->getProvider($data[1]);
            $forecasts->setProvider($provider);

            $forecasts->setForecastDate(new \DateTime($data[2]));
            $forecasts->setForecastDays(intval($data[3]));

            /** @var City $city */
            $city = $this->getCity($data[4]);
            $forecasts->setCity($city);

            $forecasts->setTemperatureHigh(floatval($data[5]));
            $forecasts->setTemperatureLow(floatval($data[6]));
            $forecasts->setTemperatureHighDeviation(floatval($data[7]));
            $forecasts->setHumidity(floatval($data[8]));
            $forecasts->setPressure(floatval($data[9]));
            $forecasts->setTemperatureLowDeviation(floatval($data[10]));
            $forecasts->setHumidityDeviation(floatval($data[11]));
            $forecasts->setPressureDeviation(floatval($data[12]));

            $manager->persist($forecasts);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 7;
    }
}
