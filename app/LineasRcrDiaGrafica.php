<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LineasRcrDiaGrafica extends Model
{
    //use SoftDeletes;

    public $table = 'PRG_MTV_LIN_RCRS_DIA';

    protected $fillable = [
        'imsi',
        'timestamp'
    ];
}