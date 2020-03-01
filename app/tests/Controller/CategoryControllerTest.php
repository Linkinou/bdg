<?php


namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;

class CategoryControllerTest extends WebTestCase
{
    /**
     * @group legacy
     */
    public function testTopicCreation()
    {
        $client = static::createClient();
        $crawler = $this->logIn($client);
        $crawler = $client->request('GET', '/category/mycategorytest/new-topic');

        $form = $crawler->selectButton('Créer le sujet')->form();

        $form['topic_creation_form[topicTitle]'] = 'Mon nouveau sujet de test';
        $form['topic_creation_form[content]'] = 'Mon contenu de test';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Mon nouveau sujet de test")')->count()
        );
    }

    private function logIn(AbstractBrowser $client, $username = 'rootkid@epotato.net', $password = 'password') : Crawler
    {
        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('Connexion')->form();

        $form['email'] = 'rootkid@epotato.net';
        $form['password'] = 'password';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();

        return $crawler;
    }
}