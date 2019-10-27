<?php 
// tests/Utilities/KonstantyTest.php
namespace App\Tests\Utilities;

use PHPUnit\Framework\TestCase;
use App\Utilities\Konstanty;

class KonstantyTest extends TestCase
{
  public function testKonstanty()
  {
    $this->assertStringContainsString(' ver.', Konstanty::VERZIA_PRIDAJ, "Chyba, konštanta VERZIA_PRIDAJ neobsahuje slovo ver.");
  }


}

?>
