<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function show($brand)
    {
        $brands = ['microsoft', 'hp', 'dell', 'lenovo'];
        
        if (!in_array($brand, $brands)) {
            abort(404);
        }

        return view("brands.{$brand}");
    }
}