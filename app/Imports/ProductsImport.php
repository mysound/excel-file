<?php

namespace App\Imports;

use App\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Intervention\Image\Facades\Image as ImageInt;
use Storage;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function __construct($brand) 
    {
        $this->brand = $brand;
    }

    public function model(array $row)
    {   
        $upc = $row['upc'];
        if(strlen($upc) < 13) {
            $upc = '0'.$upc;
        }

        $title = $row['artist'].' - '.$row['title'].' ('.$row['type'].') Preorder';

        $discription = '<h1>'.$title.'</h1><p>This is preorder</p><ul><li>Label: '.$row['label'].'</li><li>UPC: '.$upc.'</li><li>Format: '.$row['type'].'</li><li>Street Date: '.$row['street_date'].'</li></ul><h3 style="color:red;">PLEASE READ A DESCRIPTION</h3><p>A release day is subject to be changed by a label<br>All releases are very limited when released<br>The seller has a right to cancel any preorder if a label can’t fulfill a preorder for us</p>';

        $picURL = 'https://ams.dmmserver.com/media/640/'.substr($upc, 0, 8).'/'.$upc.'.jpg';

        $quantity = 1;

        $price = 0;
        switch ($row['price']):
        case (($row['price'] >= 0) and ($row['price'] <= 3.99)):
            $price = ($row['price'] + 4)*2;
            break;
        case (($row['price'] >= 4) and ($row['price'] <= 5.99)):
            $price = ($row['price'] + 3)*2;
            break;
        case (($row['price'] >= 6) and ($row['price'] <= 9.99)):
            $price = ($row['price'] + 2)*2;
            break;
        case (($row['price'] >= 10) and ($row['price'] <= 11.99)):
            $price = ($row['price'] + 1)*2;
            break;
        case (($row['price'] >= 12) and ($row['price'] <= 13.99)):
            $price = $row['price']*2;
            break;
        case (($row['price'] >= 12) and ($row['price'] <= 13.99)):
            $price = $row['price']*2;
            break;
        case (($row['price'] >= 14) and ($row['price'] <= 15.99)):
            $price = $row['price']/100*90;
            $price = $price+$row['price'];
            break;
        case (($row['price'] >= 16) and ($row['price'] <= 19.99)):
            $price = $row['price']/100*80;
            $price = $price+$row['price'];
            break;
        case (($row['price'] >= 20) and ($row['price'] <= 22.99)):
            $price = $row['price']/100*70;
            $price = $price+$row['price'];
            break;
        case (($row['price'] >= 23) and ($row['price'] <= 28.99)):
            $price = $row['price']/100*60;
            $price = $price+$row['price'];
            break;
         case ($row['price'] >= 29):
            $price = $row['price']/100*50;
            $price = $price+$row['price'];
            break;
        endswitch;


        
        return $product  = Product::updateOrCreate(
            [
                'upc'   => $upc
            ],
            [
               'category'       => $row['category'],
               'upc'            => $upc,
               'title'          => $title,
               'description'    => $discription,
               'picURL'         => $picURL,
               'quantity'       => $quantity,
               'price'          => $price,
               'sku'            => $this->brand.'-'.$upc,
            ]
        );
    }
}