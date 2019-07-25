<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\Models\Model as BaseModel;

class Client extends Model
{
   

    protected $fillable = ['razon_social', 'disabled', 'audit_created_by', 'audit_updated_by'];

   

}
