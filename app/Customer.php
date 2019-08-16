<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //

    protected $fillable = ['codigo', 'nombre', 'calle', 'cuit', 'localidad', 'provincia', 
    'disabled', 'client_id', 'audit_created_by', 'audit_updated_by'];


    public function client(){
        return $this->belongsTo('App\Client');
    }
}
