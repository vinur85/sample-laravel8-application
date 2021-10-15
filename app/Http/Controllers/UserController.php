<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\PurchasedCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Constructor function for AuthController
     */
    public function __construct()
    {

    }

    /**
     * Get user details
     *
     * @return string
     */
    public function index()
    {
        return $this->sendResponse(['name'=> Auth::user()->name]);
    }

    /**
     * Show purchased products
     */
    public function products()
    {
        $purchasedProducts = Auth::user()->products()->get();
        return ProductCollection::make($purchasedProducts)->toJson();
    }
}
