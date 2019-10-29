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
        //ini_set('max_execution_time', 300);
        
    	return Excel::download(new ProductsExport, 'products.xlsx');
        //(new ProductsExport)->queue('invoices.csv');
        //(new ProductsExport)->store('products.xlsx');
        return back()->withSuccess('Export started!');

    }

    public function import(Request $request)
    {
        //ini_set('max_execution_time', 300);

    	//$product = Excel::queueImport(new ProductsImport, $request->file('import_file'));

        Excel::import(new ProductsImport($request->brand), $request->file('import_file'));

    	return redirect('/')->with('success', 'All good!');
    }

    public function addimage()
    {
        ini_set('max_execution_time', 300);

        $url = "https://cpanel.redeyeworldwide.com/media/covers/201374707676.jpg";

        // Размер картинки - 88Mb
        //$url = "https://cpanel.redeyeworldwide.com/media/covers/201374707506.jpg";
        
        //dd(getimagesize($url));
        $imagetitle = substr($url, strrpos($url, '/') + 1);
        $imageType = exif_imagetype($url);
        if ($imageType <= 3) {

            $ch = curl_init($url);

            curl_setopt_array($ch,[
                CURLOPT_TIMEOUT => 60,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_NOPROGRESS => 0,
                CURLOPT_BUFFERSIZE => 1024,
                CURLOPT_PROGRESSFUNCTION => function ($ch, $dwnldSize, $dwnld, $upldSize, $upld) {
                    if ($dwnld > 1024 * 1024 * 5) {
                        return -1;
                    }
                },
                CURLOPT_SSL_VERIFYPEER => 1,
                CURLOPT_SSL_VERIFYHOST => 2,
            ]);
            $raw   = curl_exec($ch);    // Скачаем данные в переменную
            $info  = curl_getinfo($ch); // Получим информацию об операции
            $error = curl_errno($ch);   // Запишем код последней ошибки

            curl_close($ch);

            if(!$error) {
                $picture = ImageInt::make($url)
                    ->resize(500, null, function ($constraint) { $constraint->aspectRatio(); } )
                    ->encode('jpg',100);
                Storage::disk('images')->put($imagetitle, $picture);
                return "OK";
            } else {
                 return "No";
            }
        }
    }
}
