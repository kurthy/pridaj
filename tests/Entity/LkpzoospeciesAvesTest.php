<?php
// tests/Entity/LkpzoospeciesAvesTest.php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\LkpzoospeciesAves;

class LkpzoospeciesAvesTest extends TestCase
{
  public function testPolozkaId()
  {
    $lkpzoospeciesaves = new LkpzoospeciesAves(); 
    $this->assertNull($lkpzoospeciesaves->getId());
  }
  
  public function testPolozkaGenusSpecies()
  {
    $GenusSpecies = new LkpzoospeciesAves();
    $chPom        = 'Sýkorka veľká';
    $GenusSpecies->setLkpzoospeciesGenusSpecies($chPom);
    $this->assertSame($chPom,$GenusSpecies->getLkpzoospeciesGenusSpecies());
  }

  public function testPolozkaLat()
  {
    $Lat      = new LkpzoospeciesAves();
    $chPomLat = 'Parus major';
    $Lat->setLkpzoospeciesLat($chPomLat);
    $this->assertSame($chPomLat,$Lat->getLkpzoospeciesLat());
  }

  public function testPolozkaSk()
  {
    $Sk      = new LkpzoospeciesAves();
    $chPomSk = 'Sýkorka veľká';
    $Sk->setLkpzoospeciesSk($chPomSk);
    $this->assertSame($chPomSk, $Sk->getLkpzoospeciesSk());
  }
  public function testPolozkaDynamicId()
  {
    $objAves = new LkpzoospeciesAves();
    $iDynId  = 5551;
    $objAves->setLkpzoospeciesDynamicId($iDynId);
    $this->assertSame($iDynId, $objAves->getLkpzoospeciesDynamicId());
  }

  public function testPolozkaSubspecorder()
  {
    $objSubspecorder = new LkpzoospeciesAves();
    $iSubspecorder = 10;
    $objSubspecorder->setLkpzoospeciesSubspecorder($iSubspecorder);
    $this->assertSame($iSubspecorder, $objSubspecorder->getLkpzoospeciesSubspecorder());
  }

  public function testPolozkaNajc()
  {
    $objLkpAves = new LkpzoospeciesAves();
    $iPomNajc   = 350;
    $objLkpAves->setLkpzoospeciesNajc($iPomNajc);
    $this->assertSame($iPomNajc, $objLkpAves->getLkpzoospeciesNajc());
  }
}

?>
