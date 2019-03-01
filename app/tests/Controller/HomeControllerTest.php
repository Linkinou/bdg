<?php


namespace App\tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    /**
     * @group legacy
     */
    public function testHomepageSuccess()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}