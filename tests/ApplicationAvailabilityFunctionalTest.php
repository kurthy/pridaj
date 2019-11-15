<?php 
// tests/ApplicationAvailabilityFunctionalTest.php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
     /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient([],[
    'PHP_AUTH_USER' => 'sano@e-svet.biz',
    'PHP_AUTH_PW'   => 'sanokurthy',
    ]);
        $client->request('GET', $url);

        $this->assertResponseIsSuccessful();
    }

    public function urlProvider()
    {
        yield ['/'];
        yield ['/sk/'];
        yield ['/en/'];
        yield ['/sk/zoology/'];
        yield ['/en/zoology/'];
        yield ['/en/osymfonii'];
        yield ['/sk/osymfonii'];
        yield ['/sk/zoology/new'];
        yield ['/en/zoology/new'];
        yield ['/sk/impsubory/'];
        yield ['/en/impsubory/'];
        yield ['/sk/impsubory/new'];
        yield ['/en/impsubory/new'];

        // ...
    }

}

?>
