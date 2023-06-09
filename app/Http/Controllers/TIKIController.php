<?php

namespace App\Http\Controllers;

use App\Models\tiki;
use Illuminate\Http\Request;


class TIKIController extends Controller
{
    public function getProducts()
    {
        $products = tiki::all();
        return response()->json($products);
    }
   
}
