<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'PADM_USUARIO_GENERAL';
    protected $primaryKey = 'usuario';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username'
    ];

    public function getAuthPassword()
    {
        return $this->pass();
    }

    /**
     * Get the user that owns the phone.
     */
    public function pass()
    {
        return $this->belongsTo(UserPass::class,'usuario','usuario')->where('estado',1)->first()->clave;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,'padm_usuarioperfil','usuario','perfil');
    }

    public function authorizeRoles($roles)
    {
        abort_unless($this->hasAnyRole($roles), 401);
        return true;
    }

    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true; 
            }   
        }
        return false;
    }

    public function hasRole($role)
    {
        if ($this->roles()->where('nombre', $role)->first()) {
            return true;
        }
        return false;
    }
}
