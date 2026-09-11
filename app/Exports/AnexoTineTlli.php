<?php

namespace App\Exports;

use App\ANEXO_TINE_TLLI;
use Maatwebsite\Excel\Concerns\FromCollection;

class AnexoTineTlli implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ANEXO_TINE_TLLI::all();
    }
}
