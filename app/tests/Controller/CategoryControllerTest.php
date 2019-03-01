<?php


namespace App\tests\Controller;


use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class CategoryControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @group legacy
     */
    public function testTopicCreation()
    {
        $crawler = $this->logIn();
        $crawler = $this->client->request('GET', '/category/mycategorytest/new-topic');

        $form = $crawler->selectButton('Créer le sujet')->form();

        $form['topic_creation_form[topicTitle]'] = 'Mon nouveau sujet de test';
        $form['topic_creation_form[content]'] = 'Mon contenu de test';

        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Unfortunately we can't be redirected to the last page automatically
        $lastPageLink = $crawler->filterXPath('//*[@id=\'last-page\']')->link();
        $crawler = $this->client->click($lastPageLink);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Mon nouveau sujet de test")')->count()
        );
    }

    private function logIn($username = 'rootkid@epotato.net', $password = 'password') : Crawler
    {
        $crawler = $this->client->request('GET', '/');
        $form = $crawler->selectButton('Connexion')->form();

        $form['email'] = 'rootkid@epotato.net';
        $form['password'] = 'password';

        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        return $crawler;
    }
}