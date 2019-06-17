<?php

namespace App\Imports;

use App\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Intervention\Image\Facades\Image as ImageInt;
use Storage;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {        
        $product = Product::firstOrNew(
            [
                'upc'   => $row['upc']
            ]
        );

        $product->title = $row['title'];
        $product->price = $row['price'];

        if (isset($row['image'])) {          
            $imagetitle = substr($row['image'], strrpos($row['image'], '/') + 1);
            $picture = ImageInt::make($row['image'])
                ->resize(500, null, function ($constraint) { $constraint->aspectRatio(); } )
                ->encode('jpg',100);
            Storage::disk('images')->put($imagetitle, $picture);
            $product->image = $imagetitle;
        } else {
            $product->image = '';
        }


        return $product;
        /*return $product  = Product::updateOrCreate(
            [
                'upc'   => $row['upc']
            ],
            [
                'title' => $row['title'], 
                'price' => $row['price'],
                'upc'   => $row['upc'],
                'image' => $image
            ]
        );*/
    }
}
