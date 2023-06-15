<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;

class SalesController extends BaseController
{
    public function create(Request $request){

        if ($request->isJson()) {
            $input = $request->json()->all();
        } else {
            $input = $request->all();
        }

        foreach ($input as $key =>$value){
            $sale =  new Sale();

            $sale->product_id = $input[$key]['product_id'];
            $sale->quantity = $input[$key]['quantity'];

            $sale->save();

            $product =  Product::find($input[$key]['product_id']);
            $product->stock_qty = $product->stock_qty - $input[$key]['quantity'];
            $product->update();
        }

        return $this->sendResponse('', 'Sales created Successfully');

    }
}
