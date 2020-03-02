<?php


namespace App\tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin() : void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $crawler = $this->login($client, $crawler);

        $this->assertGreaterThan(
            0,
            $crawler->filter('a[href=\'/logout\']')->count()
        );
    }

    public function testSignIn() : void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('S\'inscrire')->form();

        $form['username'] = 'newUser';
        $form['email'] = 'newUser@myemail.com';
        $form['password'] = 'nicepassword';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();

        $crawler = $this->login($client, $crawler, 'newUser@myemail.com', 'nicepassword');

        $this->assertGreaterThan(
            0,
            $crawler->filter('a[href=\'/logout\']')->count()
        );
    }

    private function login(AbstractBrowser $client, Crawler $crawler, $username = 'rootkid@epotato.net', $password = 'password') : Crawler
    {
        $form = $crawler->selectButton('Connexion')->form();

        $form['email'] = 'rootkid@epotato.net';
        $form['password'] = 'password';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();

        return $crawler;
    }
}