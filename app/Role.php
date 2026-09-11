<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "padm_perfil";
    protected $primaryKey = 'perfil';
    protected $guarded = [];

    public function users()
    {
        $userModel = User::class;

        return $this->belongsToMany($userModel, 'padm_usuarioperfil')
                    ->select(app($userModel)->getTable().'.*')
                    ->union($this->hasMany($userModel))->getQuery();
    }

    public function permissions()
    {
        return $this->belongsToMany(Voyager::modelClass('Permission'),'PADM_ADM_PERMISSION_ROLE');
    }

}
