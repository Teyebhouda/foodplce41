<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postal extends Model
{
    protected $table = 'food_postal';
    protected $primaryKey = 'id';
    
    public function citypostal()
    {      
        return $this->hasOne('App\City', 'id', 'city');
    }

}
?>