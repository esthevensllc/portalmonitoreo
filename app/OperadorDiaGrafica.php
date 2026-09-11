<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperadorDiaGrafica extends Model
{
    //use SoftDeletes;

    public $table = 'PRG_MTV_CVM_MSTRS_DIA';

    protected $fillable = [
        'ubigeo',
        'timestamp'
    ];
}