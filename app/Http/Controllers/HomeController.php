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
        session('client_selected', $client_selected);
        $clients = Client::where('disabled', 0)->get();

        if (empty($client_selected)) {
            $firstClient = $clients->first();
            $client_selected =$firstClient->id;

        }else {
            $firstClient['id'] = $client_selected;
        }


        $remitos = Remito::select('remitos.*')->where('remitos.client_id', $client_selected)
                        ->join('customers','customer_id', 'customers.id');

        $busqueda = $request->input('q');
        if( $busqueda!= ''){
            $remitos = $remitos->where('referencia', 'LIKE', '%'.$busqueda.'%');
            $remitos = $remitos->orWhere('remitos.calle', 'LIKE', '%'.$busqueda.'%');
            $remitos = $remitos->orWhere('customers.nombre','LIKE', '%'.$busqueda.'%');
            $remitos = $remitos->orWhere('remitos.numero_remito', 'LIKE', '%'.$busqueda.'%');
        }

        $remitos = $remitos->orderBy('remitos.created_at', 'desc')
                    ->with('customer');

        $remitos = $remitos->paginate(20)
                        ->appends([ 'q' => $busqueda,
                                'clients' => $clients,
                                'client_selected' => $client_selected])
                        ;
        $q = $busqueda;
        return view('index', compact('remitos', 'clients', 'client_selected', 'q'))->withQuery($q)
                ->with('i', ($request->input('page', 1) - 1) * 20);




    }



}
