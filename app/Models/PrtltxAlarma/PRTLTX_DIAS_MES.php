<?php

namespace App\Models\PrtltxAlarma;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class PRTLTX_DIAS_MES extends Model
{
    protected $table = 'PRTLTX_dias_mes';
    protected $primaryKey = 'n_dias';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'n_dias',
        'mes'
    ];
}