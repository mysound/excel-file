<?php

namespace App\Imports;

use App\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return $product  = Product::updateOrCreate(
            [
                'upc'   => $row['upc']
            ],
            [
                'title' => $row['title'], 
                'price'   => $row['price'],
                'upc'   => $row['upc'],
            ]
        );
    }
}
