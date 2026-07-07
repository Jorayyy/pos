<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Adding image_url allows Laravel to write the link data to SQLite safely
    protected $fillable = [
        'name', 
        'barcode', 
        'retail_price',
        'cost_price', 
        'stock_quantity', 
        'category', 
        'image_url' // <-- COPY AND PASTE THIS EXACT LINE HERE
    ];
}
