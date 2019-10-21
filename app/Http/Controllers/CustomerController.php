<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Client;
use Auth;


use Illuminate\Http\Request;
use App\Http\Resources\CustomerCollection;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //



        $customers = Customer::orderBy('nombre','ASC');
        
        if( $request->buscar){
            $customers = $customers->where('nombre', 'LIKE', '%'.$request->buscar.'%');
            $customers = $customers->orWhere('calle', 'LIKE', '%'.$request->buscar.'%');
            $customers = $customers->orWhere('codigo', 'LIKE', '%'.$request->buscar.'%');
        }
        
        $customers = $customers->paginate(20);
        return view('admin.customers.index',compact('customers'))
                ->with('i', ($request->input('page', 1) - 1) * 20);
        
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $clients = Client::select('id', 'razon_social')->where('disabled', 0)
        ->orderBy('razon_social')->get();

        $clients = 
        array_pluck($clients, 'razon_social', 'id');
        return view('admin.customers.create',compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $this->validate($request, [
            'codigo'    => 'required|unique:customers,codigo',
            'codigo_valkimia'    => 'required|unique:customers,codigo_valkimia',
            'nombre'    => 'required',
            'cuit'      => 'required',
            'calle'     => 'required',
            'localidad' => 'required',
            'provincia' => 'required',

            'client_id' => 'required'
        ]);



        $customer = new Customer([
            'codigo'    => $request->get('codigo'),
            'codigo_valkimia'    => $request->get('codigo_valkimia'),
            'nombre'    => $request->get('nombre'),
            'cuit'      => $request->get('cuit'),
            'client_id' => $request->get('client_id'),
            'calle'     => $request->get('calle'),
            'localidad' => $request->get('localidad'),
            'provincia' => $request->get('provincia'),


            'disabled' => 0
          ]);
    
          $customer->audit_created_by = Auth::user()->email;
    
    
          $customer->save();
    
          return redirect()->route('customers.index')
                            ->with('success','Customer created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $customer = Customer::find($id);

        $clients = Client::select('id', 'razon_social')->where('disabled', 0)
                    ->orderBy('razon_social')->get();

        $clients = 
        array_pluck($clients, 'razon_social', 'id');
        //return response()->json($client);
        return view('admin.customers.edit',compact('customer', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    
    public function update($id, Request $request)
    {


         $this->validate($request, [
             'codigo_valkimia' => 'required',
            'nombre'    => 'required',
            'cuit'      => 'required',
            'calle'     => 'required',
            'localidad' => 'required',
            'provincia' => 'required',

            'client_id' => 'required'
        ]);

        $existeCodigoValkimia = Customer::where('id', '<>', $id)
                                ->where('codigo_valkimia', $request->codigo_valkimia)->first();
        if($existeCodigoValkimia){
            $this->validate($request, [
                'codigo_valkimia'    => 'required|unique:customers,codigo_valkimia',
                ]);
        }
        
        $customer = Customer::find($id);
        //$client->update($request->all());
        $customer->codigo = $request->codigo;
        $customer->codigo_valkimia = $request->codigo_valkimia;

        $customer->nombre = $request->nombre;
        $customer->cuit = $request->cuit;
        $customer->calle = $request->calle;
        $customer->localidad = $request->localidad;

        $customer->provincia = $request->provincia;
        $customer->client_id = $request->client_id;
        


        $customer->disabled = ($request->disabled == "on")?1:0;
        $customer->audit_updated_by = Auth::user()->email;

        $customer->save();
        return redirect()->route('customers.index')
                            ->with('success','customer Updated successfully');
        
    }



    /*
    * API
    */

    public function list( Request $request){

        $customers = Customer::where('client_id', $request->client_id)
                    ->orderBy('nombre')->get();
        return response()->json($customers, 200);
    }
}
