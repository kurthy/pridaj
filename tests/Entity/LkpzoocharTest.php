<?php 
// tests/Entity/LkpzoocharTest.php
 
namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Lkpzoochar;


class LkpzoocharTest extends TestCase
{
  protected static $oChar;

  public static function setUpBeforeClass(): void
  {
    self::$oChar = new Lkpzoochar();
  }

  public function testPolozkaId()
  {
    $this->assertNull(self::$oChar->getId());
  }

  public function testPolozkaIdCh()
  {
    $chPom = 'D12'; 
    self::$oChar->setLkpzoocharIdCh($chPom);
    $this->assertSame($chPom, self::$oChar->getLkpzoocharIdCh());
  }

  public function testPolozkaMeaning()
  {
     $chPom = 'Vylietané mláďatá';
     self::$oChar->setLkpzoocharMeaning($chPom);
     $this->assertSame($chPom,self::$oChar->getLkpzoocharMeaning());
  }

  public function testPolozkaPopularmeaning()
  {
    $chPom = 'Dlšie vysvetlenie významu skratky charakteristiky';
    self::$oChar->setLkpzoocharPopularmeaning($chPom);
    $this->assertSame($chPom, self::$oChar->getLkpzoocharPopularmeaning());
  }

  public function testPolozkaDescription()
  {
    $chPom = 'Podrobné vysvetlenie významu skratky charakteristiky';
    self::$oChar->setLkpzoocharDescription($chPom);
    $this->assertSame($chPom, self::$oChar->getLkpzoocharDescription());
  }

  public function testPolozkaExpertise()
  {
    $chPom = 'Presne neviem načo využiť túto položku, no už tu bola';
    self::$oChar->setLkpzoocharExpertise($chPom);
    $this->assertSame($chPom, self::$oChar->getLkpzoocharExpertise());
  }

  public function testPolozkaComborder()
  {
    $iPom  = 13;
    self::$oChar->setLkpzoocharComborder($iPom);
    $this->assertSame($iPom, self::$oChar->getLkpzoocharComborder());

  }

  public function testPolozkaPopularmeaningde()
  {
    $chPom = 'Beschreibung in deutsche Sprache';
    self::$oChar->setLkpzoocharPopularmeaningde($chPom);
    $this->assertSame($chPom, self::$oChar->getLkpzoocharPopularmeaningde());
  }

  public function testPolozkaPopularmeaningen()
  {
    $chPom = 'Description of meaning in english language';
    self::$oChar->setLkpzoocharPopularmeaningen($chPom);
    $this->assertSame($chPom, self::$oChar->getLkpzoocharPopularmeaningen());
  }

  public function testPolozkaPopularmeaninghu()
  {
    $chPom = 'Magyarázat magyar nyelven';
    self::$oChar->setLkpzoocharPopularmeaninghu($chPom);
    $this->assertSame($chPom, self::$oChar->getLkpzoocharPopularmeaninghu());
  }

  public function testPolozkaLkpzoocharaves()
  {
    $bPom  = true;
    self::$oChar->setLkpzoocharAves($bPom);
    $this->assertSame($bPom, self::$oChar->getLkpzoocharAves());
  }

  public function testPolozkaLkpzoocharMammalia()
  {
    $bPom  = true;
    self::$oChar->setLkpzoocharMammalia($bPom);
    $this->assertSame($bPom, self::$oChar->getLkpzoocharMammalia());
  }
/*
  public function testPolozkaLkpzoocharOdonata()
  {
    $bPom  = true;
    self::$oChar->setLkpzoocharOdonata($bPom);
    $this->assertSame($bPom, self::$oChar->getLkpzoocharOdonata());
  }
 */
  
}

?>
