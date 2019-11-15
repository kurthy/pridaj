<?php 
// tests/Controller/PrehladControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PrehladControllerTest extends WebTestCase
{

  public function testIndex()
  {
    //neprihlásený
    $client = self::createClient();
    $client->request('GET', '/');

    //mal by dostať nechybovú odpoveď
    $this->assertEquals(200, $client->getResponse()->getStatusCode());

    //načítame do crawlera
    $crawler = $client->request('GET', '/');

    //mal by mať možnosť príhlásiť sa a uvítanie
    $this->assertSelectorTextContains('html p', 'Vitajte');
    $this->assertSelectorTextContains('html a[href*="login"]', 'Prihláste');
    $this->assertSelectorTextContains('html a[href*="en"]', 'Sign');


    //nemal by mať menu kým sa neprihlási
    $this->assertEquals(
        0,
        $crawler->filter('html a[href*="sk"]:contains("Domov")')->count()
    );

    //prihlásenie
    $client = self::createClient([],[
    'PHP_AUTH_USER' => 'sano@e-svet.biz',
    'PHP_AUTH_PW'   => 'sanokurthy',
    ]);
    $client->request('GET', '/');
    $this->assertEquals(200, $client->getResponse()->getStatusCode());

    //načítame do crawlera
    $crawler = $client->request('GET', '/');
    $this->assertSelectorTextContains('html h2', 'Pridávanie pozorovaní ');
    $this->assertSelectorTextContains('html p', 'Prihlásený:  sano@e-svet.biz');


    $this->assertGreaterThan(
        0,
        $crawler->filter('html a[href*="zoology"]:contains("Cez mal")')->count()
    );
    $this->assertGreaterThan(
        0,
        $crawler->filter('html h2:contains("dajov")')->count()
    );


    //mal by mať menu

    $this->assertSelectorTextContains('html nav', 'Aves-Sym');

    //každej linke v menu overíme či existuje link, samotné overenie obsahu
    //vždy na príslušnom teste toho-ktorého kontroléra
    $this->assertGreaterThan(
        0,
        $crawler->filter('html a[href*="osymfonii"]:contains("Aves-Symf")')->count()
    );

    $this->assertGreaterThan(
        0,
        $crawler->filter('html a[href*="sk"]:contains("Domov")')->count()
    );
    $this->assertGreaterThan(
        0,
        $crawler->filter('html a[href*="zoology"]:contains("Zapísané pozorovania")')->count()
    );
    $this->assertGreaterThan(
        0,
        $crawler->filter('html a[href*="new"]:contains("Založiť nový")')->count()
    );

    //sú dve linky, jedna v menu a druhú v texte
    $this->assertEquals(
        2,
        $crawler->filter('html a[href*="impsu"]:contains("z excelu")')->count()
    );

    //aj tu sú dve linky na odhlásenie na jednej strane
    $this->assertEquals(
        2,
        $crawler->filter('html a[href*="logout"]:contains("Odhlásiť")')->count()
    );



  }
}

?>
