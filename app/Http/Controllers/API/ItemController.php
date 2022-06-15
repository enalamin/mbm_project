<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // all items
    public function index()
    {
        $items = Item::all()->toArray();
        return array_reverse($items);
    }

    // add items
    public function add(Request $request)
    {
        $item = new Item([
            'item_name' => $request->item_name,
            'item_description' => $request->item_description,
            'entry_by' => \Auth::user()->id
        ]);
        $item->save();

        return response()->json('The item successfully added');
    }

    // edit item
    public function edit($id)
    {
        $item = Item::find($id);
        return response()->json($item);
    }

    // update item
    public function update($id, Request $request)
    {
        $item = Item::find($id);
        $item->update($request->all());

        return response()->json('The item successfully updated');
    }

    // delete item
    public function delete($id)
    {
        $item = Item::find($id);
        $item->delete();

        return response()->json('The item successfully deleted');
    }
}
