<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function list(Request $request){
        $limit = $request->get('limit') ?  $request->get('limit') : 10; //per page count
        $division = Product::paginate($limit);
        return $this->sendResponse($division, 'All Product List');
    }

    public function details(Request $request)
    {
        $id = $request->route('id');
        $product = Product::where('id', $id)->firstOrFail();
        return $this->sendResponse($product, 'Product Data Read Successfully.');
    }
}
