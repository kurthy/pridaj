<?php
// src/Utilities/PrvyExcelFilter.php

namespace App\Utilities;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class PrvyExcelFilter implements IReadFilter
{
    public function readCell($column, $row, $worksheetName = '')
    {
        // Read rows 1 to 3 and columns A to J only
        if ($row >= 1 && $row <= 2) {
            if (in_array($column, range('A', 'J'))) {
                return true;
            }
        }

        return false;

    }
}
?>
