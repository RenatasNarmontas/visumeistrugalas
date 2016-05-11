<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 11/05/16
 * Time: 17:26
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Temperature;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadTemperatureData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadTemperatureData extends AbstractFixture implements OrderedFixtureInterface
{
    use LoadHelperTrait;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $fileName = __DIR__.'/temperatures.csv';
        $csv = array_map('str_getcsv', file($fileName));

        foreach ($csv as $data) {
            $temperature = new Temperature();

            $city = $this->getCity($data[1]);
            $temperature->setCity($city);

            $provider = $this->getProvider($data[2]);
            $temperature->setProvider($provider);

            $temperature->setDate(new \DateTime($data[3]));
            $temperature->setHumidity(floatval($data[4]));
            $temperature->setPressure(floatval($data[5]));
            $temperature->setTemperature(floatval($data[6]));

            $manager->persist($temperature);
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
        return 6;
    }
}
