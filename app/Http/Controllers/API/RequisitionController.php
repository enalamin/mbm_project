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
            $requisitions = $requisitions->whereIn('requisitions.status',['approved','issued']);
        }
        $requisitions = $requisitions->orderBy('requisitions.requisition_date','ASC')->get()->toArray();
        return $requisitions;
    }

    // add requisition
    public function add(Request $request)
    {
        if(\Auth::user()->role == 'employee'){
            DB::beginTransaction();
            try{
                $requisition = new Requisition([
                    'requisition_no' => $request->requisition_no,
                    'requisition_date' => $request->requisition_date,
                    'description' => $request->description,
                    'status' => 'draft',
                    'entry_by' => \Auth::user()->id
                ]);
                $requisition->save();
                $itemDetails = $request->requisitionItems;
                if($itemDetails){
                    $requisitionItems = [];
                    foreach($itemDetails as $item){
                    
                        array_push(
                            $requisitionItems,[
                                'requisition_id' => $requisition->id,
                                'item_id' => $item['item_id'],
                                'amount' => $item['quantity'],
                                'entry_by' => \Auth::user()->id
                            ]
                        );
                    }
                    //dd($requisitionItems);
                    DB::table('requisition_details')->insert($requisitionItems);
                    DB::commit();
                    return response()->json('The requisition successfully added');
                }
            }catch (\Exception $e) {
                DB::rollback();
                dd($e->getMessage());
                return response()->json('Operation failed');
                // something went wrong
            }
        }else{
            return response()->json('You do not have the permission');
        }   
    }

    // edit requisition
    public function edit($id)
    {
        $requisition = DB::table('requisitions')
        ->select('requisitions.*','requisition_details.item_id','requisition_details.amount','requisition_details.issued_quantity','items.item_name')
        ->join('requisition_details','requisition_details.requisition_id','=','requisitions.id')
        ->join('items',function($join) use($id)
        {
          $join->on('items.id', '=', 'requisition_details.item_id')
          ->where('requisition_details.requisition_id','=',$id);
       
        })
        ->where('requisitions.id','=',$id)
        ->get();
        $requisitionObject = null;
        if($requisition){
            $requisitionItems = [];
            foreach($requisition as $item){
                array_push($requisitionItems,array(
                    'item_id' => $item->item_id,
                    'amount' => $item->amount,
                    'item_name' => $item->item_name,
                    'issued_quantity' => $item->issued_quantity
                ));
            }
            $requisitionObject = array(
                'id' => $requisition[0]->id,
                'requisition_no' => $requisition[0]->requisition_no,
                'requisition_date' => $requisition[0]->requisition_date,
                'description' => $requisition[0]->description,
                'requisitionItems' => $requisitionItems
            );
        }
        //dd($requisitionObject);
        return response()->json($requisitionObject);
    }

    
    // update supplier
    public function update($id, Request $request)
    {
        if(\Auth::user()->role == 'employee'){
            DB::beginTransaction();
            try{
                $requisition = Requisition::find($id);
                $requisitionData = [
                    'requisition_no' => $request->requisition_no,
                    'requisition_date' => $request->requisition_date,
                    'description' => $request->description,
                    'status' => 'draft',
                    'update_by' => \Auth::user()->id
                ];
                $requisition->update($requisitionData);
              
                $itemDetails = $request->requisitionItems;
                if($itemDetails){
                    // delete existing items
                    DB::table('requisition_details')->where('requisition_id','=',$id)->delete();

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
                    return response()->json('The requisition successfully update');
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

    // requisition approve or reject
    public function approveOrReject(Request $request, $id)
    {
        if(\Auth::user()->role == 'admin'){
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

    public function issueRequisition(Request $request,$id)
    {
        $requistionItems = $request->requisitionItems;
        if(\Auth::user()->role == 'executive'){
            DB::beginTransaction();
            try{
                foreach($requistionItems as $item){
                    $itmeStockInfo = $this->getItemstock($item['item_id']);
                    $issuedQuantity = 0;

                    foreach($itmeStockInfo as $stockItem){
                        if($stockItem->quantity-$stockItem->used_qty >= $item['amount']-$issuedQuantity){
                            // update stock receive
                            
                            DB::table('stock_receives')->where('id','=',$stockItem->id)
                            ->update([
                                'used_qty' => ($stockItem->used_qty+$item['amount']-$issuedQuantity)
                            ]);
                            $issuedQuantity  += $item['amount']-$issuedQuantity;
                            dd($issuedQuantity);
                        } else{
                            DB::table('stock_receives')->where('id','=',$stockItem->id)
                            ->update([
                                'used_qty' => $stockItem->quantity
                            ]);
                            $issuedQuantity  += $stockItem->quantity-$stockItem->used_qty;
                        }

                        if($issuedQuantity==$item['amount']){
                            break;
                        }
                    }
                    //
                    DB::table('requisition_details')
                    ->where('item_id','=',$item['item_id'])
                    ->where('requisition_id','=',$id)
                            ->update([
                                'issued_quantity' => $issuedQuantity
                            ]);

                }
                DB::table('requisitions')->where('id','=',$id)->update(
                    [
                        'status' => 'issued',
                        'issue_by' => \Auth::user()->id,
                        'issue_date' => $request->issue_date
                    ]
                    );
                DB::commit();
                return response()->json('The requisition successfully issued');
            }catch(\Exception $e){
                DB::rollback();
                dd($e->getMessage());
                return response()->json('operation failed');
            }
        }
    }   
    
    private function getItemstock($itemId)
    {
        $itemStocks = DB::table('stock_receives')
        ->where('quantity','>','used_qty')
        ->where('item_id','=',$itemId)
        ->orderBy('receive_date','asc')
        ->get()->toArray();
        return $itemStocks;
    }
}
