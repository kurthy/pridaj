<?php
// tests/Controller/ZoologyController
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ZoologyControllerTest extends WebTestCase
{
  public function testIndex()
  {
    $client = self::createClient([],[
    'PHP_AUTH_USER' => 'sano@e-svet.biz',
    'PHP_AUTH_PW'   => 'sanokurthy',
    ]);
    $client->request('GET', '/');
    // echo $client->getResponse()->getContent();
    
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $crawler = $client->request('GET', '/sk/');
    /*
    $varPom = array();
    foreach ($crawler as $domElement) {
      $varPom[] = $domElement->ownerDocument->saveHTML($domElement); //nodeValue; //ownerDocument;
    }
    var_dump($varPom);
    */

    /*
    $aPom = $crawler->filter('html a[href*=imp]')->count();
    echo PHP_EOL.'Vysledok: '.$aPom; 
    */

    $this->assertEquals(200, $client->getResponse()->getStatusCode());

    //klikni na pridavanie cez excel subor
    $link = $crawler
              ->filter('html a[href*="imp"]:contains("z excelu")')
              ->eq(0)
              ->link()
              ;
    $crawler = $client->click($link);
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertGreaterThan(
        0,
        $crawler->filter('html h2:contains("")')->count()
    );

  } 
}

?>
