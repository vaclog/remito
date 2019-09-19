<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remito extends Model
{
    //

    //protected $dates = [ 'fecha_remito'];

    protected $fillable = ['sucursal', 'numero_remito', 'customer_id', 'fecha_remito', 
    'transporte', 'conductor', 'patente', 'calle', 'localidad', 'provincia',
    'disabled', 'client_id', 'audit_created_by', 'audit_updated_by', 'observaciones',
    'cai', 'cai_vencimiento', 'referencia'];

    //
    public function client(){
        return $this->belongsTo('App\Client');
    }

    public function customer(){
        return $this->belongsTo('App\Customer');
    }

    public function articulos(){
        return $this->hasMany('App\RemitoArticulo');
    }

    


    public function items(){
        return $this->hasMany('App\RemitoArticulo');
    }
}
