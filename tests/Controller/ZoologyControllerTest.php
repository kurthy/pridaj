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
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $crawler = $client->request('GET', '/sk/');
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertSelectorTextContains('html h2', 'Pridávanie pozorovaní ');
    $this->assertSelectorTextContains('html p', 'Prihlásený:  sano@e-svet.biz');

  } 
}

?>
