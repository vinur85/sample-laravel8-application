<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductUpdate;
use App\Http\Requests\RemoveProduct;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends Controller
{
    /**
     * Constructor for ProductsController
     */
    public function __construct()
    {

    }

    /**
     * List all products
     *
     * @return string
     */
    public function index()
    {
        return ProductCollection::make(Product::all())->toJson();
    }

    /**
     * Purchase new product
     *
     * @param Request $request
     * @return string
     */
    public function update(ProductUpdate $request)
    {
        $sku = $request->sku;
        $product = Product::where('sku', $sku)->first();
        auth()->user()->products()->syncWithoutDetaching([$product->id]);
        $purchasedProducts = auth()->user()->products()->get();
        return ProductCollection::make($purchasedProducts)->toJson();
    }

    /**
     * Remove any product
     *
     * @param Request $request
     * @param $sku
     * @return string
     */
    public function destroy($sku)
    {
        $product = Product::where('sku', $sku)->first();
        if ($product) {
            auth()->user()->products()->detach($product->id);
        } else {
            return $this->sendResponse([], 'Invalid product!', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $purchasedProducts = auth()->user()->products()->get();
        return ProductCollection::make($purchasedProducts)->toJson();
    }
}
