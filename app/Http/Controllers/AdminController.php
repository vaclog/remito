<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AdminController extends Controller
{
    //
    public function index() {
      
    $user = Auth::user();
    //$user->assignRole('configurador');
    //dd($user->can('configuracion'));
    //do operations here
    if ($user->hasRole('admin'))
        return view('admin.index');

    return response()->json("No autorizado", 500);

    }
        
}
