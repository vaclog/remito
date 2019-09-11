<?php

namespace App\Http\Controllers;


use App\Client;

use Illuminate\Http\Request;
use App\Http\Resources\ClientCollection;
use Carbon\Carbon;

use Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        
        //dd(Auth::user());
        //return new ClientCollection(Client::all());
        
        $clients = Client::orderBy('id','DESC')->paginate(5);
        return view('admin.clients.index',compact('clients'))
                ->with('i', ($request->input('page', 1) - 1) * 5);
        

        
    }

    public function list(Request $request){
        return new ClientCollection(Client::all());
    }

    public function create()
    {
        
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {

      $this->validate($request, [
        'razon_social' => 'required|unique:clients,razon_social',
        
    ]);
      $client = new Client([
        'razon_social' => $request->get('razon_social'),
        'cai' => $request->get('cai'),
        'sucursal' => $request->get('sucursal'),
        'cai_vencimiento' => ($request->get('cai_vencimiento'))?date_format(Carbon::createFromFormat('d/m/Y', $request->get('cai_vencimiento')), 'Y-m-d'):null,
        'disabled' => 0
      ]);

      $client->audit_created_by = Auth::user()->email;


      $client->save();

      return redirect()->route('clients.index')
                        ->with('success','Client created successfully');
    }


    public function edit($id)
    {
      $client = Client::find($id);
      //return response()->json($client);
      return view('admin.clients.edit',compact('client'));
    }
    public function update($id, Request $request)
    {
      $client = Client::find($id);
      //$client->update($request->all());
      $client->razon_social = $request->razon_social;
      $client->cai = $request->cai;
      $client->sucursal = $request->sucursal;
      $client->cai_vencimiento = ($request->get('cai_vencimiento'))?date_format(Carbon::createFromFormat('d/m/Y', $request->get('cai_vencimiento')), 'Y-m-d'):null;
      $client->disabled = ($request->disabled == "on")?1:0;
      $client->audit_created_by = Auth::user()->email;

      $client->save();
      return redirect()->route('clients.index')
                        ->with('success','Client Updated successfully');
    }
    public function delete($id)
    {
      $client = Client::find($id);
      $client->delete();
      return response()->json('successfully deleted');
    }

   
}
