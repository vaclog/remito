<?php

namespace App\Http\Controllers;

use App\Product;
use App\Client;
use Auth;


use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //



        $products = Product::orderBy('descripcion','ASC');
        
        if( $request->buscar){
            $products = $products->where('descripcion', 'LIKE', '%'.$request->buscar.'%');
            $products = $products->orWhere('marca', 'LIKE', '%'.$request->buscar.'%');
            $products = $products->orWhere('codigo', 'LIKE', '%'.$request->buscar.'%');
        }
        
        $products = $products->paginate(20);
        return view('admin.products.index',compact('products'))
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
        return view('admin.products.create',compact('clients'));
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
            'codigo'    => 'required|unique:products,codigo',
            'descripcion'    => 'required',
            'marca'      => 'required',
            

            'client_id' => 'required'
        ]);



        $product = new Product([
            'codigo'    => $request->get('codigo'),
            'descripcion'    => $request->get('descripcion'),
            'marca'      => $request->get('marca'),
            'client_id' => $request->get('client_id'),
           


            'disabled' => 0
          ]);
    
          $product->audit_created_by = Auth::user()->email;
    
    
          $product->save();
    
          return redirect()->route('products.index')
                            ->with('success','Product created successfully');
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $product = Product::find($id);

        $clients = Client::select('id', 'razon_social')->where('disabled', 0)
                    ->orderBy('razon_social')->get();

        $clients = 
        array_pluck($clients, 'razon_social', 'id');
        //return response()->json($client);
        return view('admin.products.edit',compact('product', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    
    public function update($id, Request $request)
    {

         $this->validate($request, [
            'codigo'    => 'required|unique:products,codigo',
            'descripcion'    => 'required',
            'marca'      => 'required',
            

            'client_id' => 'required'
        ]);

        $product = product::find($id);
        //$client->update($request->all());
        $product->codigo = $request->codigo;
        $product->descripcion = $request->descripcion;
        $product->marca = $request->marca;
      
        $product->client_id = $request->client_id;
        


        $product->disabled = ($request->disabled == "on")?1:0;
        $product->audit_updated_by = Auth::user()->email;

        $product->save();
        return redirect()->route('products.index')
                            ->with('success','product Updated successfully');
        
    }

    
}
