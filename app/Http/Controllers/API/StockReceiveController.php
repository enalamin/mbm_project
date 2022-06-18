<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StockReceive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockReceiveController extends Controller
{
    // get recive list
    public function index()
    {
        if(\Auth::user()->role == 'executive'){
            $receivelist = DB::table('stock_receives')
            ->select('stock_receives.receive_no','stock_receives.receive_date',DB::raw('count(stock_receives.item_id) as number_of_items'),'suppliers.name as supplier_name')
            ->join('suppliers','suppliers.id','=','stock_receives.supplier_id')
            ->groupBy('stock_receives.receive_no','stock_receives.receive_date','suppliers.name')
            ->orderBy('stock_receives.receive_date','ASC')->get()->toArray();
            return $receivelist;
        }else{
            return response()->json('You do not have the permission');
        }
        
    }

    // add stock receive
    public function add(Request $request)
    {
        if(\Auth::user()->role == 'executive'){
            DB::beginTransaction();
            try{
                $itemDetails = $request->receiveItems;
                if($itemDetails){
                    $receiveItems = [];
                    foreach($itemDetails as $item){
                    
                        array_push(
                            $receiveItems,array(
                                'receive_no' => $request->receive_no,
                                'receive_date' => $request->receive_date,
                                'supplier_id' => $request->supplier_id,
                                'item_id' => $item['item_id'], 
                                'quantity' => $item['quantity'],
                                'price' =>  $item['price'],
                                'entry_by' => \Auth::user()->id
                            )
                        );
                    }
                    
                    DB::table('stock_receives')->insert($receiveItems);
                    DB::commit();
                    return response()->json('The item received successfully added');
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

     // edit stock receive
     public function edit($id)
     {
         $receive = DB::table('stock_receives')
         ->select('stock_receives.*','suppliers.name as supplier_name','items.item_name')
         ->join('suppliers','suppliers.id','=','stock_receives.supplier_id')
         ->join('items',function($join) use($id)
         {
           $join->on('items.id', '=', 'stock_receives.item_id')
           ->where('stock_receives.receive_no','=',$id);
        
         })
         ->where('stock_receives.receive_no','=',$id)
         ->get();
        //dd($recieve);
         $receiveObject = null;
         if($receive){
             $receiveItems = [];
             foreach($receive as $item){
                 array_push($receiveItems,array(
                     'item_id' => $item->item_id,
                     'quantity' => $item->quantity,
                     'item_name' => $item->item_name,
                     'price' => $item->price,
                 ));
             }
             $receiveObject = array(
                 'id' => $receive[0]->id,
                 'receive_no' => $receive[0]->receive_no,
                 'receive_date' => $receive[0]->receive_date,
                 'supplier_name' => $receive[0]->supplier_name,
                 'receiveItems' => $receiveItems
             );
         }
         //dd($requisitionObject);
         return response()->json($receiveObject);
     }

     public function itemsCurrentStocks()
     {
         $currentStock = DB::table('stock_receives')
            ->select('stock_receives.item_id',DB::raw('sum(quantity-used_qty) as current_stock'),'items.item_name')
            ->join('items','items.id','=','stock_receives.item_id')
            ->groupBy('stock_receives.item_id','items.item_name')
            ->get()->toArray();
            //dd($currentStock);
        
        return $currentStock;
     }
}
