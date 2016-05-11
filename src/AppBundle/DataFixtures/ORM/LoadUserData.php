<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 30/04/16
 * Time: 01:45
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        $user1 = $userManager->createUser();
        $user1->setUsername('vip@contact.lt');
        $user1->setEmail('vip@contact.lt');
        $user1->setPassword('$2y$13$n8ld5vco2c0c4ow84000suRfDYBlHZmYsbzSv8gX8eQc5ExrARdhy');
//        $user1->setSalt('n8ld5vco2c0c4ow84000swksc0gcgck');
//        $user1->setPlainPassword('password1');
        $user1->setEnabled(true);
        $user1->setRoles(array('ROLE_ADMIN'));
        $user1->setNotifications(true);
        $this->addReference('user1', $user1);

        $user2 = $userManager->createUser();
        $user2->setUsername('info@contact.lt');
        $user2->setEmail('info@contact.lt');
        $user2->setPassword('$2y$13$irkg49a3ft44s004ck44seSuHyh9oNq9NQZFpJwVUon27sgSJuy2.');
//        $user2->setSalt('irkg49a3ft44s004ck44so88go8c8o0');
//        $user2->setPlainPassword('password2');
        $user2->setEnabled(true);
        $user2->setRoles(array('ROLE_USER'));
        $user2->setNotifications(false);
        $user2->setApi(uniqid('AdvWeatherAPI_'));

        $this->addReference('user2', $user2);

        // Update the user
        $userManager->updateUser($user1, true);
        $userManager->updateUser($user2, true);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 4;
    }
}
