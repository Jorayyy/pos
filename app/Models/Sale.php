<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['total_amount', 'amount_paid', 'change'];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
