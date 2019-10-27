<?php
// tests/Entity/LkppristupnostTest.php

namespace App\Tests\Entity;
use PHPUnit\Framework\TestCase;
use App\Entity\Lkppristupnost;

class LkppristupnostTest extends TestCase
{
  protected static $oPrist;
  
  public static function setUpBeforeClass(): void
  {
    self::$oPrist = new Lkppristupnost();
  }

  public function testPolozkaId()
  {
    $this->assertNull(self::$oPrist->getId());
  }

  public function testPolozkaPristupnost()
  {
    $chPom  = 'V';
    self::$oPrist->setLkppristupnostPristupnost($chPom);
    $this->assertSame($chPom, self::$oPrist->getLkppristupnostPristupnost());
  }

  public function testPolozkaPopissk()
  {
    $chPom  = 'Popis skratky V že znamená verejné';
    self::$oPrist->setLkppristupnostPopissk($chPom);
    $this->assertSame($chPom, self::$oPrist->getLkppristupnostPopissk());
  }

  public function testPolozkaPopisen()
  {
    $chPom  = 'Explanation of meaning V abbreviation in english language';
    self::$oPrist->setLkppristupnostPopisen($chPom);
    $this->assertSame($chPom, self::$oPrist->getLkppristupnostPopisen());  
  }

  public function testToString()
  {
    $chPomPri  = 'V';
    $chPomPop  = 'Popis položky V';
    self::$oPrist->setLkppristupnostPristupnost($chPomPri);
    self::$oPrist->setLkppristupnostPopissk($chPomPop);
    $this->assertSame($chPomPri, self::$oPrist->__toString());
  } 
}
?>
