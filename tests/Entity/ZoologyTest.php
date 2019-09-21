<?php 
// tests/Entity/ZoologyTest.php
namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Zoology;
use App\Entity\Lkppristupnost;
use DateTime;

class ZoologyTest extends TestCase
{
  public function testPolozkyId()
  {
    $oZoo = new Zoology();
    $this->assertNull($oZoo->getId());
  }

  public function testPolozkaSfGuardUserId()
  {
    $oZoo = new Zoology();
    $iPom = 22;
    $oZoo->setSfGuardUserId($iPom);
    $this->assertSame($iPom, $oZoo->getSfGuardUserId());
  }

  public function testPolozkaZoologyDate()
  {
    $oZoo = new Zoology();
    $dPom = new DateTime('2019-08-25 00:00');
    $oZoo->setZoologyDate($dPom);
    $this->assertSame($dPom, $oZoo->getZoologyDate());
  }

  public function test_Polozka_Zoology_Longitud()
  {
    $oZoo = new Zoology();
    $fPom = 17.12345;
    $oZoo->setZoologyLongitud($fPom);
    $this->assertSame($fPom, $oZoo->getZoologyLongitud());
  }

  public function test_Polozka_Zoology_Latitud()
  {
    $oZoo = new Zoology();
    $fPom = 48.12345;
    $oZoo->setZoologyLatitud($fPom);
    $this->assertSame($fPom, $oZoo->getZoologyLatitud());
  }

  public function testPolozkaZoologyLocality()
  {
    $oZoo = new Zoology();
    $chPom = 'Čermeľská dolina';
    $oZoo->setZoologyLocality($chPom);
    $this->assertSame($chPom, $oZoo->getZoologyLocality());
  }

  public function testPolozkyZoologyAccessibility()
  {

    $chPom    = 'V';

    $oZoo     = new Zoology();
    $oPristup = new Lkppristupnost();
    $oPristup->setLkppristupnostPristupnost($chPom);

    $oZoo->setZoologyAccessibility($oPristup);
    $this->assertSame($chPom, strval($oZoo->getZoologyAccessibility()));
  }

  public function testPolozkaZoologyDescription()
  {
    $oZoo = new Zoology();
    $length = 255;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, 33)];
    }
    $chPom256 = $randomString; 
    $oZoo->setZoologyDescription($chPom256);
    $this->assertSame(255, strlen($oZoo->getZoologyDescription()), ' Položka má mať maximum char255, dĺžka zapísaného reťazca je však '.strlen($chPom256));
  }

  public function testPolozkyZoologyExport()
  {

    $oZoo = new Zoology();
    $chPom = 'N';
    $oZoo->setZoologyExport($chPom);
    $this->assertSame($chPom, $oZoo->getZoologyExport(),'Zoology_export položka enum s hodnotami N,E,I,Z');
  }
}



?>
