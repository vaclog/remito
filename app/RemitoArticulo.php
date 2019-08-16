<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemitoArticulo extends Model
{
    //

    protected $fillable = ['codigo', 'descripcion', 'marca', 
    'cantidad', 'product_id', 'remito_id', 
    'disabled', 'client_id', 'audit_created_by', 'audit_updated_by'];



}
