<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 30/04/16
 * Time: 01:39
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Request;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRequestData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $request1 = new Request();
        $request1->setCity('New York');
        $request1->setUserId($this->getReference('user1'));

        $request2 = new Request();
        $request2->setCity('Beijing');
        $request2->setUserId($this->getReference('user2'));

        $manager->persist($request1);
        $manager->persist($request2);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        // Load it after LoadUserData
        return 5;
    }
}