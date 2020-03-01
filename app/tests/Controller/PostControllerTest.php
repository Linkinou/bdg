<?php


namespace App\tests\Controller;


use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class PostControllerTest extends WebTestCase
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
    public function testPostReply()
    {
        $crawler = $this->logIn();
        $crawler = $this->client->request('GET', '/topic/mon-sujet-test');

        $answerLink = $crawler->selectLink('Répondre')->first()->link();
        $crawler = $this->client->click($answerLink);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("RE: Mon sujet test")')->count()
        );

        $form = $crawler->selectButton('Répondre')->form();

        $form['topic_reply_form[content]'] = 'J\'ai posté un message !';

        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Unfortunately we can't be redirected to the last page automatically
        $lastPageLink = $crawler->filterXPath('//*[@id=\'last-page\']')->link();
        $crawler = $this->client->click($lastPageLink);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("J\'ai posté un message !")')->count()
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