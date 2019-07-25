<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = ['codigo', 'descripcion', 'marca', 'disabled', 'client_id', 'audit_created_by', 'audit_updated_by'];

    //
    public function client(){
        return $this->belongsTo('App\Client');
    }
}
