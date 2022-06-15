<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // all suppliers
    public function index()
    {
        $suppliers = Supplier::all()->toArray();
        return array_reverse($suppliers);
    }

    // add suppliers
    public function add(Request $request)
    {
        $supplier = new Supplier([
            'name' => $request->name,
            'address' => $request->address,
            'contact_no' => $request->contact_no,
            'email' => $request->email,
            'entry_by' => \Auth::user()->id
        ]);
        $supplier->save();

        return response()->json('The supplier successfully added');
    }

    // edit supplier
    public function edit($id)
    {
        $supplier = Supplier::find($id);
        return response()->json($supplier);
    }

    // update supplier
    public function update($id, Request $request)
    {
        $supplier = Supplier::find($id);
        $supplier->update($request->all());

        return response()->json('The supplier successfully updated');
    }

    // delete supplier
    public function delete($id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();

        return response()->json('The supplier successfully deleted');
    }
}
