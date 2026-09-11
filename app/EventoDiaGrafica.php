<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventoDiaGrafica extends Model
{
    //use SoftDeletes;

    public $table = 'PRG_CVM_MTV_EVENTOS';

    protected $fillable = [
        'celda_id',
        'cellname',
        'recurrencia'
    ];
}