<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class Export implements FromArray, ShouldAutoSize
{
    public $data;


    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     *  возвращает экспортируемые данные *
     */
    public function array(): array
    {
        return $this->data;
    }
}
