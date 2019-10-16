<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemitoArticulo extends Model
{
    //

    protected $fillable = ['codigo', 'descripcion', 'marca', 
    'cantidad', 'product_id', 'remito_id', 'ean13', 'lote', 'unidad_medida', 'fecha_vencimiento',
    'disabled', 'client_id', 'referencia','audit_created_by', 'audit_updated_by'];



}
