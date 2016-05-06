<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 30/04/16
 * Time: 01:39
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Request;
use AppBundle\Entity\User;
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
        /** @var User $user1 */
        $user1 = $this->getReference('user1');
        $request1->setUserId($user1->getId());

        $request2 = new Request();
        $request2->setCity('Beijing');
        /** @var User $user2 */
        $user2 = $this->getReference('user2');
        $request2->setUserId($user2->getId());

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
