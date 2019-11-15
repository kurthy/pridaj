<?php
// src/Utilities/ExcelDatum2Unix.php

namespace App\Utilities;

class ExcelDatum2Unix
{
  public function getExcelDate2Unix($dateValue = 0)
  {
    $dPomValue = ($dateValue - 25569) * 86400;
    $dPomDate  = gmdate("d.m.Y H:i:s", $dPomValue);
   return $dPomDate;

  }

  public function getUnixdate2Excel($dateValue = 0)
  {
     $excelDate = 25569 + ($dateValue / 86400);
     return $excelDate;
  }
}
?>
