<?php


namespace App\tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;

class RpgControllerTest extends WebTestCase
{

    /**
     * @var AbstractBrowser
     */
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    private function createAGame(Crawler $crawler = null)
    {
        $crawler = $this->client->request('GET', '/rpg/cristalia');

        $createGameLink = $crawler->filter('a.nk-btn')->first()->link();
        $crawler = $this->client->click($createGameLink);

        $form = $crawler->selectButton('Créer la partie')->form();
        $form['game_creation_form[title]'] = '[TEST]Ma partie[/TEST]';
        $form['game_creation_form[maxPlayingPersonas]'] = 42;
        $form['game_creation_form[description]'] = '[TEST]Ceci est la description de ma partie test[/TEST]';
        $form['game_creation_form[story]'] = '[TEST]Ceci est l\'histoire de ma partie test[/TEST]';

        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        return $crawler;
    }

    /**
     * @group legacy
     */
    public function testCreateGame()
    {
        $crawler = $this->logIn();

        $crawler = $this->createAGame($crawler);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("[TEST]Ma partie[/TEST]")')->count()
        );

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Votre partie a bien été créée")')->count()
        );
    }

    /**
     * @group legacy
     */
    public function testEditGame()
    {
        $crawler = $this->logIn();
        $crawler = $this->createAGame($crawler);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), $this->client->getResponse()->getContent());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("[TEST]Ma partie[/TEST]")')->count()
        );
        $editLink = $crawler->selectLink('Éditer')->first()->link();
        $crawler = $this->client->request($editLink->getMethod(), $editLink->getUri());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), $this->client->getResponse()->getContent());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Nombre maximum de joueurs : ")')->count()
        );
        $form = $crawler->selectButton('Créer la partie')->form();
        $form['game_creation_form[title]'] = '[TEST]Ma partie éditée[/TEST]';
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), $this->client->getResponse()->getContent());

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Votre partie a bien été éditée")')->count()
        );
    }

    /**
     * @group legacy
     */
    public function testEndGame()
    {
        $crawler = $this->logIn();
        $crawler = $this->client->request('GET', '/rpg/cristalia/jai-vu-de-la-lumiere-alors-je-suis-entre/view');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), $this->client->getResponse()->getContent());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Numquam expedita corrupti quidem earum est sit dolores. Id cupiditate et sunt suscipit")')->count()
        );
        $crawler = $this->client->clickLink('Terminer');
        $this->assertTrue($this->client->getResponse()->isRedirection());
        $crawler = $this->client->followRedirect();


        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), $this->client->getResponse()->getContent());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("La partie est terminée, merci d\'avoir joué !")')->count()
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