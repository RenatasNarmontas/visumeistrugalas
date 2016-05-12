<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 11/05/16
 * Time: 21:28
 */

namespace AppBundle\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class BaseTestSetupAbstract
 * @package AppBundle\Tests
 */
abstract class BaseTestSetupAbstract extends WebTestCase
{
    protected $client;
    protected $container;
    /** @var  EntityManager $entityManager */
    protected $entityManager;

    /**
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    protected function setUp()
    {
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->entityManager = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        if (!isset($metadata)) {
            $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        }
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();
        if (!empty($metadata)) {
            $schemaTool->createSchema($metadata);
        }
        $this->postFixtureSetup();

        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadProviderData',
            'AppBundle\DataFixtures\ORM\LoadCityData',
            'AppBundle\DataFixtures\ORM\LoadContactData',
            'AppBundle\DataFixtures\ORM\LoadUserData',
            'AppBundle\DataFixtures\ORM\LoadRequestData',
            'AppBundle\DataFixtures\ORM\LoadTemperatureData',
            'AppBundle\DataFixtures\ORM\LoadForecastData',
        ));
    }
}
