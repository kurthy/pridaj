<?php 
// tests/Entity/ZoologyTest.php
namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Zoology;
use App\Entity\Lkppristupnost;
use DateTime;

class ZoologyTest extends TestCase
{
  protected static $oZoo;
  public static function setUpBeforeClass(): void
  {
    self::$oZoo = new Zoology();
  }

  public function testPolozkyId()
  {
    $this->assertNull(self::$oZoo->getId());
  }

  public function testPolozkaSfGuardUserId()
  {
    $iPom = 22;
    self::$oZoo->setSfGuardUserId($iPom);
    $this->assertSame($iPom, self::$oZoo->getSfGuardUserId());
  }

  public function testPolozkaZoologyDate()
  {
    $dPom = new DateTime('2019-08-25 00:00');
    self::$oZoo->setZoologyDate($dPom);
    $this->assertSame($dPom, self::$oZoo->getZoologyDate());
  }

  public function test_Polozka_Zoology_Longitud()
  {
    $fPom = 17.12345;
    self::$oZoo->setZoologyLongitud($fPom);
    $this->assertSame($fPom, self::$oZoo->getZoologyLongitud());
  }

  public function test_Polozka_Zoology_Latitud()
  {
    $fPom = 48.12345;
    self::$oZoo->setZoologyLatitud($fPom);
    $this->assertSame($fPom, self::$oZoo->getZoologyLatitud());
  }

  public function testPolozkaZoologyLocality()
  {
    $chPom = 'Čermeľská dolina';
    self::$oZoo->setZoologyLocality($chPom);
    $this->assertSame($chPom, self::$oZoo->getZoologyLocality());
  }

  public function testPolozkyZoologyAccessibility()
  {

    $chPom    = 'V';

    $oPristup = new Lkppristupnost();
    $oPristup->setLkppristupnostPristupnost($chPom);

    self::$oZoo->setZoologyAccessibility($oPristup);
    $this->assertSame($chPom, strval(self::$oZoo->getZoologyAccessibility()));
  }

  public function testPolozkaZoologyDescription()
  {
    $length = 255;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, 33)];
    }
    $chPom256 = $randomString; 
    self::$oZoo->setZoologyDescription($chPom256);
    $this->assertSame(255, strlen(self::$oZoo->getZoologyDescription()), ' Položka má mať maximum char255, dĺžka zapísaného reťazca je však '.strlen($chPom256));
  }

  public function testPolozkyZoologyExport()
  {

    $chPom = 'N';
    self::$oZoo->setZoologyExport($chPom);
    $this->assertSame($chPom, self::$oZoo->getZoologyExport(),'Zoology_export položka enum s hodnotami N,E,I,Z');
  }
}
?>
