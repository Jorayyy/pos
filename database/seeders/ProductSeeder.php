<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Lucky Me Pancit Canton Extra Hot', 'barcode' => '480001655111', 'retail_price' => 16.00, 'stock_quantity' => 50, 'category' => 'Noodles'],
            ['name' => 'Coke Original Taste 290ml', 'barcode' => '480000123456', 'retail_price' => 18.00, 'stock_quantity' => 24, 'category' => 'Beverages'],
            ['name' => 'Chippy Barbecue Flavor Large', 'barcode' => '480002233445', 'retail_price' => 20.00, 'stock_quantity' => 15, 'category' => 'Snacks'],
            ['name' => 'Kopiko Black 3-in-1 Sachet', 'barcode' => '480005566778', 'retail_price' => 10.00, 'stock_quantity' => 100, 'category' => 'Coffee/Sugar'],
            ['name' => 'Silver Swan Soy Sauce 200ml', 'barcode' => '480009988776', 'retail_price' => 15.00, 'stock_quantity' => 12, 'category' => 'Condiments'],
            ['name' => 'Safeguard Pure White 60g', 'barcode' => '480004433221', 'retail_price' => 32.00, 'stock_quantity' => 8, 'category' => 'Personal Care'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
