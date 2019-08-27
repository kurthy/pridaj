<?php 
// tests/Entity/LkpzoocharTest.php
 
namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Lkpzoochar;

class LkpzoocharTest extends TestCase
{
  public function testPolozkaId()
  {
    $oChar = new Lkpzoochar();
    $this->assertNull($oChar->getId());
  }

  public function testPolozkaIdCh()
  {
    $oChar = new Lkpzoochar();
    $chPom = 'D12'; 
    $oChar->setLkpzoocharIdCh($chPom);
    $this->assertSame($chPom, $oChar->getLkpzoocharIdCh());
  }

  public function testPolozkaMeaning()
  {
     $oChar = new Lkpzoochar();   
     $chPom = 'Vylietané mláďatá';
     $oChar->setLkpzoocharMeaning($chPom);
     $this->assertSame($chPom,$oChar->getLkpzoocharMeaning());
  }

  public function testPolozkaPopularmeaning()
  {
    $oChar = new Lkpzoochar();
    $chPom = 'Dlšie vysvetlenie významu skratky charakteristiky';
    $oChar->setLkpzoocharPopularmeaning($chPom);
    $this->assertSame($chPom, $oChar->getLkpzoocharPopularmeaning());
  }

  public function testPolozkaDescription()
  {
    $oChar = new Lkpzoochar();
    $chPom = 'Podrobné vysvetlenie významu skratky charakteristiky';
    $oChar->setLkpzoocharDescription($chPom);
    $this->assertSame($chPom, $oChar->getLkpzoocharDescription());
  }

  public function testPolozkaExpertise()
  {
    $oChar = new Lkpzoochar();
    $chPom = 'Presne neviem načo využiť túto položku, no už tu bola';
    $oChar->setLkpzoocharExpertise($chPom);
    $this->assertSame($chPom, $oChar->getLkpzoocharExpertise());
  }

  public function testPolozkaComborder()
  {
    $oChar = new Lkpzoochar();
    $iPom  = 13;
    $oChar->setLkpzoocharComborder($iPom);
    $this->assertSame($iPom, $oChar->getLkpzoocharComborder());

  }

  public function testPolozkaPopularmeaningde()
  {
    $oChar = new Lkpzoochar();
    $chPom = 'Beschreibung in deutsche Sprache';
    $oChar->setLkpzoocharPopularmeaningde($chPom);
    $this->assertSame($chPom, $oChar->getLkpzoocharPopularmeaningde());
  }

  public function testPolozkaPopularmeaningen()
  {
    $oChar = new Lkpzoochar();
    $chPom = 'Description of meaning in english language';
    $oChar->setLkpzoocharPopularmeaningen($chPom);
    $this->assertSame($chPom, $oChar->getLkpzoocharPopularmeaningen());
  }

  public function testPolozkaPopularmeaninghu()
  {
    $oChar = new Lkpzoochar();
    $chPom = 'Magyarázat magyar nyelven';
    $oChar->setLkpzoocharPopularmeaninghu($chPom);
    $this->assertSame($chPom, $oChar->getLkpzoocharPopularmeaninghu());
  }
}

?>
