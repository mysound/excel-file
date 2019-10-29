<?php

namespace App\Exports;

use App\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;

//class ProductsExport implements FromQuery
class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    /*use Exportable;
    public function query()
    {
        return Product::query();
    }*/

    public function collection()
    {
        return Product::all();
    }

    public function map($row): array
    {
        $globalShipping = '1';
        if($row['price'] >= 40) {
            $globalShipping = '0';
        } else {
            $globalShipping = '1';
        }
        return [
            $row->id,
            $row->category,
            $row->upc,
            $row->title,
            $row->description,
            '1000',
            $row->picURL,
            $row->quantity,
            'FixedPrice',
            $row->price,
            'GTC',
            '11230',
            '1',
            'skovyla@yahoo.com',
            'Flat',
            'USPSMedia',
            '0.00',
            $globalShipping,
            '30',
            $row->sku,
            'ReturnsAccepted',
            'Buyer',
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            '*Category',
            'Product:UPC',
            'Title',
            'Description',
            '*ConditionID',
            'PicURL',
            '*Quantity',
            '*Format',
            '*StartPrice',
            '*Duration',
            '*Location',
            'PayPalAccepted',
            'PayPalEmailAddress',
            'ShippingType',
            'ShippingService-1:Option',
            'ShippingService-1:Cost',
            'GlobalShipping',
            'DispatchTimeMax',
            'CustomLabel',
            'ReturnsAcceptedOption',
            'ShippingCostPaidByOption'
        ];
    }
}
