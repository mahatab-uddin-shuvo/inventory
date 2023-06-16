<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;
use Validator;
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
            $product =  Product::find($input[$key]['product_id']);
            if($product->stock_qty<1){
                $data = [
                    'name' => $product->name,
                    'msg' => 'this product is not available'
                ];
                return $this->sendError($data, 'Logical issue');
            }elseif ($product->stock_qty < $input[$key]['quantity']){
                $data = [
                    'name' => $product->name,
                    'msg' => 'We do not have your total product quantity'
                ];
                return $this->sendError($data, 'Logical issue');
            }
            else{
                $sale->product_id = $input[$key]['product_id'];
                $sale->quantity = $input[$key]['quantity'];

                $sale->save();

                $product->stock_qty = $product->stock_qty - $input[$key]['quantity'];
                $product->update();
            }
        }

        return $this->sendResponse('', 'Sales created Successfully');

    }
}
