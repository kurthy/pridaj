<?php
// tests/Entity/LkppristupnostTest.php

namespace App\Tests\Entity;
use PHPUnit\Framework\TestCase;
use App\Entity\Lkppristupnost;

class LkppristupnostTest extends TestCase
{
  public function testPolozkaId()
  {
    $oPrist = new Lkppristupnost();
    $this->assertNull($oPrist->getId());
  }

  public function testPolozkaPristupnost()
  {
    $oPrist = new Lkppristupnost();
    $chPom  = 'V';
    $oPrist->setLkppristupnostPristupnost($chPom);
    $this->assertSame($chPom, $oPrist->getLkppristupnostPristupnost());
  }

  public function testPolozkaPopissk()
  {
    $oPrist = new Lkppristupnost();
    $chPom  = 'Popis skratky V že znamená verejné';
    $oPrist->setLkppristupnostPopissk($chPom);
    $this->assertSame($chPom, $oPrist->getLkppristupnostPopissk());
  }

  public function testPolozkaPopisen()
  {
    $oPrist = new Lkppristupnost();
    $chPom  = 'Explanation of meaning V abbreviation in english language';
    $oPrist->setLkppristupnostPopisen($chPom);
    $this->assertSame($chPom, $oPrist->getLkppristupnostPopisen());  
  }

  public function testToString()
  {
    $oPrist   = new Lkppristupnost();
    $chPomPri  = 'V';
    $chPomPop  = 'Popis položky V';
    $oPrist->setLkppristupnostPristupnost($chPomPri);
    $oPrist->setLkppristupnostPopissk($chPomPop);
    $this->assertSame($chPomPri, $oPrist->__toString());
  } 
}

?>
