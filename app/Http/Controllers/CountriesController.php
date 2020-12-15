<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
 
    public function list()
    {
        return response()->json(Countries::where('is_active',1)->get());
    }

    public function view($id)
    {
        return response()->json(Countries::find($id));
    }

    public function create(Request $request)
    {
        
        $Countries = Countries::create($request->all());
        return response()->json(['status' => "Country Created Successfully!",$Countries], 201);

    }

    public function update($id, Request $request)
    {
        $Countries = Countries::findOrFail($id);
        $Countries->update($request->all());
        return response()->json(['status' => "Country Updated Successfully!",$Countries], 201);
    }

    public function delete($id)
    {
        Countries::findOrFail($id)->delete();
        return response(['status' => "Country ID ".$id." Deleted Successfully!"], 200);
    }
}