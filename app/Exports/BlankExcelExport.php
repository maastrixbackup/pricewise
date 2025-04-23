<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class BlankExcelExport implements FromArray
{
    private $headers;

    public function __construct($headers)
    {
        $this->headers = $headers;
    }

    public function array(): array
    {
        return [$this->headers];
    }
}
