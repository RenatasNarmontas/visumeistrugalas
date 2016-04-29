<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 30/04/16
 * Time: 00:47
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Provider;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProviderData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $provider1 = new Provider();
        $provider1->setName('OpenWeatherMap');
        $provider1->setUrl('http://openweathermap.org');
        $this->addReference('OpenWeatherMap', $provider1);

        $provider2 = new Provider();
        $provider2->setName('Yahoo');
        $provider2->setUrl('http://www.yahoo.com/');
        $this->addReference('Yahoo', $provider2);

        $provider3 = new Provider();
        $provider3->setName('Weather Underground');
        $provider3->setUrl('http://www.wunderground.com/');
        $this->addReference('Weather Underground', $provider3);

        $provider4 = new Provider();
        $provider4->setName('Advanced Weather');
        $provider4->setUrl('http://localhost/');
        $this->addReference('Advanced Weather', $provider4);

        $manager->persist($provider1);
        $manager->persist($provider2);
        $manager->persist($provider3);
        $manager->persist($provider4);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}