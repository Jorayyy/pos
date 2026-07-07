<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sari-Sari Store Smart POS</title>
    <script src="https://tailwindcss.com"></script>
    <script defer src="https://jsdelivr.net"></script>
</head>
<body class="bg-amber-50 font-sans min-h-screen text-slate-800">

    <!-- Top Dashboard Header Navigation Bar -->
    <nav class="bg-gradient-to-r from-amber-500 to-orange-500 text-white p-4 shadow-md flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <!-- Shop Front SVG Visual Anchor Icon -->
            <svg class="w-8 h-8 text-yellow-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            <span class="text-xl font-bold tracking-wide">Tindahan POS Lite</span>
        </div>
        <div class="text-sm bg-orange-600 px-3 py-1 rounded-full font-medium shadow-inner">SQLite Engine Active</div>
    </nav>

    <!-- Main App Container Layout Framework -->
    <div class="container mx-auto p-4 grid grid-cols-1 lg:grid-cols-12 gap-6" x-data="posSystem()">
        
        <!-- Left Column Pane: Dynamic Product Grid Array (7 Columns Wide) -->
        <div class="lg:col-span-7 bg-white p-6 rounded-2xl shadow-sm border border-amber-100">
            <h2 class="text-lg font-bold text-slate-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Available Items
            </h2>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @foreach($products as $product)
                <button 
                    @click="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->retail_price }}, {{ $product->stock_quantity }})"
                    class="p-4 bg-gradient-to-b from-amber-50 to-white hover:from-amber-100 hover:to-amber-50 border border-amber-200 rounded-xl text-left transition transform active:scale-95 shadow-sm group">
                    <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">{{ $product->category }}</div>
                    <div class="font-bold text-slate-800 group-hover:text-orange-600 transition-colors line-clamp-2 h-12">{{ $product->name }}</div>
                    <div class="flex justify-between items-center mt-3">
                        <span class="text-lg font-black text-slate-900">₱{{ number_format($product->retail_price, 2) }}</span>
                        <span class="text-xs px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md font-medium">Qty: {{ $product->stock_quantity }}</span>
                    </div>
                </button>
                @endforeach
            </div>
        </div>

        <!-- Right Column Pane: Active Cart Counter & Total Computations (5 Columns Wide) -->
        <div class="lg:col-span-5 bg-white p-6 rounded-2xl shadow-md border-2 border-orange-400 flex flex-col justify-between min-h-[500px]">
            <div>
                <h2 class="text-lg font-bold text-slate-700 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Current Order Basket
                </h2>

                <!-- Render Dynamic Cart Records List -->
                <div class="space-y-3 max-h-64 overflow-y-auto pr-1">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <div class="pr-2 flex-1">
                                <span class="font-bold text-slate-800 text-sm block leading-tight" x-text="item.name"></span>
                                <div class="text-xs text-slate-500 mt-1">₱<span x-text="item.price.toFixed(2)"></span> each</div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center border bg-white rounded-lg shadow-inner">
                                    <button @click="updateQty(item.id, -1)" class="px-2 py-1 text-slate-500 hover:bg-slate-100 font-bold rounded-l-lg">-</button>
                                    <span class="px-3 py-1 font-mono text-sm text-slate-800" x-text="item.quantity"></span>
                                    <button @click="updateQty(item.id, 1)" class="px-2 py-1 text-slate-500 hover:bg-slate-100 font-bold rounded-r-lg">+</button>
                                </div>
                                <span class="font-bold text-slate-900 w-16 text-right">₱<span x-text="(item.price * item.quantity).toFixed(2)"></span></span>
                            </div>
                        </div>
                    </template>
                    <template x-if="cart.length === 0">
                        <div class="text-center py-12 text-slate-400 text-sm">Basket is empty. Click items on the left to add.</div>
                    </template>
                </div>
            </div>

            <!-- Mathematical Computations & Checkout Section Header -->
            <div class="mt-6 pt-4 border-t-2 border-dashed border-slate-200">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-slate-600 font-medium">Total Amount Due</span>
                    <span class="text-3xl font-black text-orange-600">₱<span x-text="getTotal().toFixed(2)"></span></span>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Customer Cash Rendered (₱)</label>
                    <input type="number" x-model="amountPaid" placeholder="0.00" class="w-full p-3 border-2 border-slate-200 rounded-xl text-xl font-mono text-right focus:border-orange-500 focus:outline-none bg-slate-50 shadow-inner">
                </div>

                <div class="flex justify-between items-center mb-4 p-3 bg-emerald-50 text-emerald-800 rounded-xl font-medium border border-emerald-200" x-show="amountPaid >= getTotal() && cart.length > 0">
                    <span>Customer Change:</span>
                    <span class="text-xl font-black">₱<span x-text="(amountPaid - getTotal()).toFixed(2)"></span></span>
                </div>

                <button 
                    @click="submitCheckout()" 
                    :disabled="cart.length === 0 || amountPaid < getTotal()"
                    class="w-full py-4 bg-gradient-to-r from-orange-500 to-amber-500 disabled:from-slate-300 disabled:to-slate-400 text-white font-bold text-lg rounded-xl shadow-lg hover:from-orange-600 hover:to-amber-600 transition transform active:scale-[0.99] flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Complete Sale
                </button>
            </div>
        </div>

    </div>

    <!-- Reactive Core Engine (State Management Logic Script) -->
    <script>
        function posSystem() {
            return {
                cart: [],
                amountPaid: '',
                addToCart(id, name, price, maxStock) {
                    let found = this.cart.find(item => item.id === id);
                    if (found) {
                        if (found.quantity < maxStock) found.quantity++;
                    } else {
                        this.cart.push({ id, name, price, quantity: 1, maxStock });
                    }
                },
                updateQty(id, change) {
                    let found = this.cart.find(item => item.id === id);
                    if (!found) return;
                    found.quantity += change;
                    if (found.quantity > found.maxStock) found.quantity = found.maxStock;
                    if (found.quantity <= 0) {
                        this.cart = this.cart.filter(item => item.id !== id);
                    }
                },
                getTotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                },
                async submitCheckout() {
                    let response = await fetch('/checkout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            items: this.cart,
                            amount_paid: this.amountPaid
                        })
                    });
                    let result = await response.json();
                    if (result.success) {
                        alert(`Transaction Complete! Change: ₱${result.change.toFixed(2)}`);
                        this.cart = [];
                        this.amountPaid = '';
                        window.location.reload();
                    } else {
                        alert(result.error || 'Checkout process failed.');
                    }
                }
            }
        }
    </script>
</body>
