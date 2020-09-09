<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\Models\Model as BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class Client extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['razon_social', 'disabled', 'audit_created_by', 'audit_updated_by'];



}
