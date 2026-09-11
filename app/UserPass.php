<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class UserPass extends Model
{
    use Notifiable;

    protected $table = 'padm_usuarioclave';
    protected $primaryKey = 'USUARIO';
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usuario','clave', 'fecha_insercion',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'fecha_insercion' => 'datetime',
    ];

    /**
     * Get the user that owns the phone.
     */
    public function user()
    {
        return $this->belongsTo(User::class,'usuario','usuario');
    }

    /**
     * Get the user that owns the phone.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class,'usuario','usuario')->where('estado',1);
    }
    
}
