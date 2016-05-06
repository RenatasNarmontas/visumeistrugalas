<?php

namespace ContactsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contacts');

        $this->assertContains('Contacts', $client->getResponse()->getContent());
    }
}
