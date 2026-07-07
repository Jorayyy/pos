<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = ['sale_id', 'product_id', 'quantity', 'price_at_sale'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
