<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
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
        $client_selected = $request->input('client_selected');
        
        $clients = Client::where('disabled', 0)->get();

        if (empty($client_selected)) {
            $firstClient = $clients->first();    
            $client_selected =$firstClient->id;

        }else {
            $firstClient['id'] = $client_selected;
        }
        

        $remitos = Remito::where('disabled', 0)
                    ->where('client_id', $client_selected)
                    ->with('customer');

        $remitos = $remitos->paginate(20);

        return view('index', compact('remitos', 'clients', 'client_selected'))
                ->with('i', ($request->input('page', 1) - 1) * 20);


       
        
    }
}
