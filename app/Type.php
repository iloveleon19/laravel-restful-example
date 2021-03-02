<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = [
        'name',
        'sort'
    ];

    public function animals(){
        return $this->hasMany('App\Animal', 'type_id', 'id');
    }
}
