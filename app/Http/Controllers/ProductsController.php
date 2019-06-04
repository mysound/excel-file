<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductsController extends Controller
{
    public function index() 
    {
    	$products = Product::all();

    	return $products;
    }

    public function export()
    {
    	return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function import(Request $request)
    {
    	Excel::import(new ProductsImport, $request->file('import_file'));

    	return redirect('/')->with('success', 'All good!');
    }
}
