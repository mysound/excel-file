<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Facades\Image as ImageInt;
use Storage;

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

    public function addimage()
    {
        $url = "https://dbsides.com/storage/images/texas-flood-stevie-ray-vaughan-double-trouble-15583365000.jpg";
        //$imagetitle = str_slug('led zeppelin') . $url->getClientOriginalExtension();
        $imagetitle = substr($url, strrpos($url, '/') + 1);
        $picture = ImageInt::make($url)
                ->resize(500, null, function ($constraint) { $constraint->aspectRatio(); } )
                ->encode('jpg',100);
        Storage::disk('images')->put($imagetitle, $picture);
        return "OK";
    }
}
