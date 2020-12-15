<?php

namespace App\Http\Controllers;

use App\Models\Currencies;
use Illuminate\Http\Request;

class CurrenciesController extends Controller
{
 
    public function list()
    {
        return response()->json(Currencies::where('is_active',1)->get());
    }

    public function view($id)
    {
        return response()->json(Currencies::find($id));
    }

    public function create(Request $request)
    {
        
        $Currencies = Currencies::create($request->all());
        return response()->json(['status' => "Currency Created Successfully!",$Currencies], 201);

    }

    public function update($id, Request $request)
    {
        $Currencies = Currencies::findOrFail($id);
        $Currencies->update($request->all());
        return response()->json(['status' => "Currency Updated Successfully!",$Currencies], 201);
    }

    public function delete($id)
    {
        Currencies::findOrFail($id)->delete();
        return response(['status' => "Currency ID ".$id." Deleted Successfully!"], 200);
    }
}