<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['title', 'upc', 'price', 'image', 'category', 'picURL', 'description', 'quantity', 'sku'];
}
