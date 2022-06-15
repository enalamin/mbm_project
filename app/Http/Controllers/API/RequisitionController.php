<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequisitionController extends Controller
{
    // all requisitoin
    public function index()
    {
        $requisitions = DB::table('requisitions')
        ->select('requisitions.*','users.name as created_by')
        ->join('users','users.id','=','requisitions.entry_by');

        if(\Auth::user()->role == 'employee'){
            $requisitions = $requisitions->where('requisitions.entry_by','=',\Auth::user()->id);
        }

        if(\Auth::user()->role == 'executive'){
            $requisitions = $requisitions->where('requisitions.status','=','approved');
        }
        $requisitions = $requisitions->orderBy('requisitions.requisition_date','ASC')->get()->toArray();
        return array_reverse($requisitions);
    }

    // add requisition
    public function add(Request $request)
    {
        if(\Auth::user()->role == 'employee'){
            DB::beginTransaction();
            try{
                $supplier = new Requisition([
                    'requisition_no' => $request->requisition_no,
                    'requisition_date' => $request->requisition_date,
                    'description' => $request->description,
                    'status' => 'draft',
                    'entry_by' => \Auth::user()->id
                ]);
                $supplier->save();
                $itemDetails = $request->requisitionItems;
                if($itemDetails){
                    $requisitionItems = [];
                    foreach($itemDetails as $item){
                    
                        array_push(
                            $requisitionItems,array(
                                'requisition_id' => $supplier->id,
                                'item_id' => $item['item_id'],
                                'amount' => $item['quantity'],
                                'entry_by' => \Auth::user()->id
                            )
                        );
                    }
                    
                    DB::table('requisition_details')->insert($requisitionItems);
                    DB::commit();
                    return response()->json('The requisition successfully added');
                }
            }catch (\Exception $e) {
                DB::rollback();
                //dd($e->getMessage());
                return response()->json('Operation failed');
                // something went wrong
            }
        }else{
            return response()->json('You do not have the permission');
        }
        

        
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

    // requisition approve or reject
    public function approveOrReject(Request $request, $id)
    {
        try{
            
            DB::table('requisitions')->where('id','=',$id)->update(
                [
                    'status' => $request->status,
                    'update_by' => \Auth::user()->id
                ]
                );

            return response()->json('The supplier successfully deleted');
        }catch (\Exception $e) {
             dd($e->getMessage());
             return response()->json('Operation failed');
             // something went wrong
        }
    }
}
