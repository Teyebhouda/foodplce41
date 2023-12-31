<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'food_city';
    protected $primaryKey = 'id';


    public function postals()
    {
        return $this->hasMany('App\Postal', 'city', 'id');
    }

}
?>