<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Credit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get();
        $salesHistory = Sale::latest()->take(20)->get();
        $totalSalesAmount = Sale::sum('total_amount');
        $credits = Credit::latest()->get();

        // Calculate absolute capital costs and gross profits
        $totalCapitalCost = 0;
        foreach (Sale::all() as $sale) {
            $items = json_decode($sale->items_json, true) ?: [];
            foreach ($items as $item) {
                // Fetch direct cost price historical matches
                $prodMatch = Product::find($item['id'] ?? 0);
                $costPrice = $prodMatch ? $prodMatch->cost_price : ($item['price'] * 0.7); 
                $totalCapitalCost += ($costPrice * $item['quantity']);
            }
        }
        $netProfitMargin = $totalSalesAmount - $totalCapitalCost;

        return view('pos.index', compact('products', 'salesHistory', 'totalSalesAmount', 'credits', 'netProfitMargin'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            $totalAmount = 0;
            $itemsToProcess = [];
            $receiptItems = [];

            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->find($item['id']);
                
                if ($product->stock_quantity < $item['quantity']) {
                    return response()->json(['error' => "Kulang ang stock para sa: {$product->name}"], 400);
                }

                $subtotal = $product->retail_price * $item['quantity'];
                $totalAmount += $subtotal;

                $itemsToProcess[] = ['product' => $product, 'quantity' => $item['quantity']];
                
                $receiptItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (float)$product->retail_price,
                    'quantity' => (int)$item['quantity'],
                    'subtotal' => (float)$subtotal
                ];
            }

            $change = $request->amount_paid - $totalAmount;
            if ($change < 0) return response()->json(['error' => 'Kulang ang bayad.'], 400);

            $sale = Sale::create([
                'total_amount' => $totalAmount, 
                'amount_paid' => $request->amount_paid, 
                'change' => $change,
                'items_json' => json_encode($receiptItems)
            ]);

            foreach ($itemsToProcess as $i) {
                $i['product']->decrement('stock_quantity', $i['quantity']);
            }

            return response()->json([
                'success' => true, 
                'change' => (float)$change,
                'sale_id' => str_pad($sale->id, 5, '0', STR_PAD_LEFT),
                'timestamp' => now()->timezone('Asia/Manila')->format('M d, Y h:i A'),
                'items' => $receiptItems,
                'total' => (float)$totalAmount,
                'paid' => (float)$request->amount_paid
            ]);
        });
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'retail_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'barcode' => 'nullable|string',
            'image_url' => 'nullable|string',
        ]);
        Product::create($request->all());
        return redirect()->back();
    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'retail_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'barcode' => 'nullable|string',
            'image_url' => 'nullable|string',
        ]);
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return redirect()->back();
    }

        public function destroyProduct($id)
    {
        // 1. Force the database driver connection to turn off foreign constraints completely
        DB::purge(); 
        DB::unprepared("PRAGMA foreign_keys = OFF;");
        
        // 2. Locate and remove the product row cleanly from your records
        $product = Product::findOrFail($id);
        $product->delete();
        
        // 3. Turn the safety locks back on for regular operations
        DB::unprepared("PRAGMA foreign_keys = ON;");

        return redirect()->back();
    }


    public function storeCredit(Request $request)
    {
        Credit::create($request->all());
        return redirect()->back();
    }

    public function toggleCredit($id)
    {
        $credit = Credit::findOrFail($id);
        $credit->status = $credit->status === 'Unpaid' ? 'Paid' : 'Unpaid';
        $credit->save();
        return redirect()->back();
    }

    public function destroyCredit($id)
    {
        Credit::findOrFail($id)->delete();
        return redirect()->back();
    }
}
