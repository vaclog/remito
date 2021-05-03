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


        $remitos = Remito::select('remitos.*')
                        ->where('remitos.client_id', $client_selected)
                        ->join('customers','customer_id', 'customers.id');

        $busqueda = '%'.$request->input('q').'%';
        if( $busqueda!= '%%'){
            $remitos->where(function($query) use ($busqueda){
                $query->orWhere('referencia', 'LIKE', $busqueda);
                 $query->orWhere('remitos.calle', 'LIKE', $busqueda);
                 $query->orWhere('customers.nombre','LIKE', $busqueda);
                 $query->orWhere('remitos.numero_remito', 'LIKE', $busqueda);
            });

        }

        $remitos = $remitos->orderBy('remitos.created_at', 'desc')
                    ->with('customer');

        // $remitos = $remitos->toSql();
        // dd($remitos);
        $remitos = $remitos->paginate(20)
                        ->appends([ 'q' => $request->input('q'),
                                'clients' => $clients,
                                'client_selected' => $client_selected])
                        ;
        $q =  $request->input('q');
        return view('index', compact('remitos', 'clients', 'client_selected', 'q'))->withQuery($q)
                ->with('i', ($request->input('page', 1) - 1) * 20);




    }



}
