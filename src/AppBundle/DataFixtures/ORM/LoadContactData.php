<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 30/04/16
 * Time: 01:32
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Contact;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadContactData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadContactData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $contact1 = new Contact();
        $contact1->setEmail('vip@contact.lt');
        $contact1->setMessage('Please contact me ASAP!');

        $contact2 = new Contact();
        $contact2->setEmail('info@contact.lt');
        $contact2->setMessage('Please provide more information');

        $contact3 = new Contact();
        $contact3->setEmail('no.spam@contact.lt');
        $contact3->setMessage('Could you add Klaipeda/Lithuania?');

        $manager->persist($contact1);
        $manager->persist($contact2);
        $manager->persist($contact3);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}
