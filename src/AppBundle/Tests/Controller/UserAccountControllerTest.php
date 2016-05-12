<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 10/05/16
 * Time: 00:31
 */

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\BaseTestSetupAbstract;

/**
 * Class UserAccountControllerTest
 * @package AppBundle\Tests\Controller
 */
class UserAccountControllerTest extends BaseTestSetupAbstract
{
    /**
     * Check main page and links availability
     */
    public function testMainPageLinksAvailability()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful());

        // Logo and menu with word "Weather"
        $this->assertCount(2, $crawler->filter('a:contains("Weather")'));
        $this->assertCount(1, $crawler->filter('a:contains("API")'));
        $this->assertCount(1, $crawler->filter('a:contains("Providers")'));
        $this->assertCount(1, $crawler->filter('a:contains("Login")'));
        $this->assertCount(1, $crawler->filter('a:contains("Register")'));
    }

    /**
     * Check login link
     */
    public function testLoginLink()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $loginLink = $crawler->filter('a:contains("Login")')->eq(0)->link();
        $loginPage = $client->click($loginLink);
        $this->assertCount(1, $loginPage->filter('span:contains("Login")'));
    }

    /**
     * Check elements
     */
    public function testRegisterLink()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $registerLink = $crawler->filter('a:contains("Register")')->eq(0)->link();
        $registerPage = $client->click($registerLink);
        $this->assertCount(1, $registerPage->filter('span:contains("Register")'));

        // Check for input types (email and password)
        $this->assertCount(1, $registerPage->filter('input[id=fos_user_registration_form_email]'));
        $this->assertCount(1, $registerPage->filter('input[id=fos_user_registration_form_plainPassword]'));
        // Check notification checkbox
        $this->assertCount(1, $registerPage->filter('input[id=fos_user_registration_form_notifications]'));
        // Check terms
        $this->assertCount(1, $registerPage->filter('p[class=terms]'));
    }

    /**
     * Provide valid credentials
     */
    public function testLoginSuccess()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $loginLink = $crawler->filter('a:contains("Login")')->eq(0)->link();
        $loginPage = $client->click($loginLink);

        $buttonCrawlerNode = $loginPage->selectButton('Log in');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'vip@contact.lt',
            '_password' => 'password1'
        ));
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        // Check for email in header
        $this->assertEquals(
            'vip@contact.lt',
            trim(
                explode(
                    PHP_EOL,
                    $crawler->filter('div[class=navbar-right-buttons]')->first()->text()
                )[1]
            )
        );
    }

    /**
     * Provide invalid credentials for login screen
     */
    public function testLoginWrongPassword()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $loginLink = $crawler->filter('a:contains("Login")')->eq(0)->link();
        $loginPage = $client->click($loginLink);

        $buttonCrawlerNode = $loginPage->selectButton('Log in');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'vip@contact.lt',
            '_password' => 'wrong_password'
        ));
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        // Check that there are no errors
        $this->assertEquals('Invalid credentials.', $crawler->filter('span[class=help-block]')->first()->text());
    }

    public function testGetJsonWithCorrectApi()
    {
        $client = static::createClient();
        $client->request('GET', '/api/AdvWeatherAPI_57337d6c313d2/forecast');
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $responseData = $response->getContent();

        // Check that we received json
        $this->assertTrue(
            false !== strpos($responseData, '{"Status":"OK","Text":"Thank you for being our customer"}]')
        );
    }

    public function testGetJsonWithIncorrectApi()
    {
        $client = static::createClient();
        $client->request('GET', '/api/doesntexist/forecast');
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $responseData = $response->getContent();

        // Check that we received json
        $this->assertTrue(
            false !== strpos($responseData, '[{"Status":"Error","Text":"API key is expired and\/or not valid"}]')
        );
    }
}
