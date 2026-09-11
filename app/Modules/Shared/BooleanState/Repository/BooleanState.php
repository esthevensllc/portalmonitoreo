<?php

namespace App\Modules\Shared\BooleanState\Repository;

use stdClass;

class BooleanState
{
    public static function list(): array {
        $si = new stdClass();
        $si->id = 'NO';
        $si->nombre = 'NO';
        $no = new stdClass();
        $no->id = 'SI';
        $no->nombre = 'SI';
        return [$no, $si];
    }

    public static function default(){
        return 'NO';
    }
}
