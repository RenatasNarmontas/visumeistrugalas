<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 30/04/16
 * Time: 01:15
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\City;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadCityData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadCityData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $city1 = new City();
        $city1->setName('Vilnius');
        $city1->setCountry('Lithuania');
        $city1->setCountryIso3166('LT');
        $this->addReference('Vilnius', $city1);

        $city2 = new City();
        $city2->setName('Paris');
        $city2->setCountry('France');
        $city2->setCountryIso3166('FR');
        $this->addReference('Paris', $city2);

        $city3 = new City();
        $city3->setName('Berlin');
        $city3->setCountry('Germany');
        $city3->setCountryIso3166('DE');
        $this->addReference('Berlin', $city3);

        $city4 = new City();
        $city4->setName('London');
        $city4->setCountry('UK');
        $city4->setCountryIso3166('GB');
        $this->addReference('London', $city4);

        $manager->persist($city1);
        $manager->persist($city2);
        $manager->persist($city3);
        $manager->persist($city4);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}
