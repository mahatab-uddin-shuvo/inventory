<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;

class PurchasesController extends BaseController
{
    public function create(Request $request){

        if ($request->isJson()) {
            $input = $request->json()->all();
        } else {
            $input = $request->all();
        }

        foreach ($input as $key =>$value){
            $purchases =  new Purchase();

            $purchases->product_id = $input[$key]['product_id'];
            $purchases->quantity = $input[$key]['quantity'];

            $purchases->save();

            $product =  Product::find($input[$key]['product_id']);
            $product->stock_qty = $product->stock_qty + $input[$key]['quantity'];
            $product->update();
        }

        return $this->sendResponse('', 'Purchase created Successfully');

    }
}
