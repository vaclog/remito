<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Remito;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index( Request $request)
    {

        $remitos = Remito::where('disabled', 0)->with('customer');

        $remitos = $remitos->paginate(20);

        return view('index',compact('remitos'))
        ->with('i', ($request->input('page', 1) - 1) * 20);


       
        
    }
}
