<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sari-Sari Store Smart POS</title>
    <style>
        * { box-sizing: border-box; font-family: system-ui, -apple-system, sans-serif; margin: 0; padding: 0; }
        body { background-color: #fffbeb; color: #334155; padding-bottom: 40px; }
        
        .store-nav { background: linear-gradient(to right, #f59e0b, #ea580c); color: white; padding: 16px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .nav-brand { display: flex; align-items: center; gap: 8px; font-size: 1.25rem; font-weight: bold; }
        .nav-badge { font-size: 0.875rem; background: #c2410c; padding: 4px 12px; border-radius: 9999px; font-weight: 500; }
        
        .main-container { display: grid; grid-template-columns: 1fr; gap: 20px; padding: 20px; width: 100%; margin: 0 auto; }
        @media (min-width: 1200px) { .main-container { grid-template-columns: 6fr 3fr 3fr; } }
        
        .secondary-grid { display: grid; grid-template-columns: 1fr; gap: 20px; padding: 0 20px 20px 20px; width: 100%; margin: 0 auto; }
        @media (min-width: 768px) { .secondary-grid { grid-template-columns: 1fr 1fr; } }

        .panel-block { background: white; padding: 20px; border-radius: 16px; border: 1px solid #fef3c7; box-shadow: 0 1px 3px rgba(0,0,0,0.05); height: calc(100vh - 120px); display: flex; flex-direction: column; overflow: hidden; }
        .panel-orange { border: 2px solid #fb923c; }
        .panel-emerald { border: 2px solid #34d399; }
        .panel-purple { border: 2px solid #a855f7; }
        
        .panel-title { font-size: 1.15rem; font-weight: bold; color: #334155; margin-top: 0; margin-bottom: 12px; display: flex; align-items: center; justify-content: center; gap: 6px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; flex-shrink: 0; position: relative; text-align: center; }
        .scrollable-body { flex-grow: 1; overflow-y: auto; padding-right: 2px; }
        
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(135px, 1fr)); gap: 12px; }
        .product-button { background: white; border: 1px solid #fde68a; border-radius: 14px; overflow: hidden; display: flex; flex-direction: column; text-align: left; cursor: pointer; transition: transform 0.1s; width: 100%; padding: 0; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .product-button:active { transform: scale(0.96); }
        .product-button:disabled { background: #f1f5f9; border-color: #e2e8f0; opacity: 0.5; cursor: not-allowed; }
        
        .prod-img-box { width: 100%; height: 95px; background: #fffbeb; display: flex; align-items: center; justify-content: center; overflow: hidden; border-bottom: 1px solid #fef3c7; position: relative; }
        .prod-img { width: 100%; height: 100%; object-fit: cover; }
        .prod-avatar-fallback { font-size: 2rem; font-weight: bold; color: #d97706; text-shadow: 1px 1px 0 white; }
        .prod-details-content { padding: 10px; display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1; width: 100%; }
        .prod-cat { font-size: 0.65rem; font-weight: 700; color: #b45309; text-transform: uppercase; margin-bottom: 2px; }
        .prod-name { font-weight: bold; color: #1e293b; height: 38px; overflow: hidden; font-size: 0.8rem; line-height: 1.3; }
        .prod-meta { display: flex; justify-content: space-between; align-items: center; margin-top: 6px; }
        .prod-price { font-size: 0.95rem; font-weight: 900; color: #0f172a; }
        .prod-qty { font-size: 0.65rem; padding: 2px 4px; background-color: #f1f5f9; color: #475569; border-radius: 4px; }
        
        .search-container-box { margin-bottom: 14px; flex-shrink: 0; position: relative; display: flex; gap: 8px; }
        .search-input-field { width: 100%; padding: 10px 12px 10px 36px; border: 2px solid #fde68a; border-radius: 10px; font-size: 0.9rem; outline: none; background-color: #fffdf5; }
        .search-input-field:focus { border-color: #ea580c; background-color: white; }
        .search-svg-icon { position: absolute; left: 12px; top: 11px; color: #a1a1aa; width: 16px !important; height: 16px !important; }
        
        .barcode-quick-input { width: 150px; padding: 10px; border: 2px solid #34d399; border-radius: 10px; font-size: 0.9rem; font-family: monospace; outline: none; background: #f0fdf4; text-align: center; }
        .barcode-quick-input:focus { border-color: #059669; background: white; }
        .basket-item { display: flex; justify-content: space-between; align-items: center; background-color: #f8fafc; padding: 10px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 8px; }
        .qty-controls { display: flex; align-items: center; border: 1px solid #cbd5e1; background: white; border-radius: 6px; overflow: hidden; }
        .qty-btn { padding: 2px 8px; border: none; background: transparent; cursor: pointer; font-weight: bold; }
        .qty-val { font-family: monospace; font-size: 0.85rem; padding: 0 2px; }
        
        .checkout-box { margin-top: 12px; padding-top: 12px; border-top: 2px dashed #e2e8f0; flex-shrink: 0; }
        .row-due { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .val-due { font-size: 1.5rem; font-weight: 900; color: #ea580c; }
        .input-cash { width: 100%; padding: 8px; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1.25rem; font-family: monospace; text-align: right; outline: none; }
        .change-box { display: none; justify-content: space-between; align-items: center; margin: 8px 0; padding: 8px 12px; background-color: #d1fae5; color: #065f46; border-radius: 8px; font-weight: bold; }
        .btn-submit { width: 100%; padding: 12px; background: linear-gradient(to right, #ea580c, #f59e0b); color: white; font-weight: bold; font-size: 1rem; border: none; border-radius: 8px; cursor: pointer; }
        .btn-submit:disabled { background: #cbd5e1; color: #94a3b8; cursor: not-allowed; }
        
        .mgmt-table { width: 100%; border-collapse: collapse; font-size: 0.78rem; }
        .mgmt-table th { padding: 6px; background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; text-align: left; }
        .mgmt-table td { padding: 6px; border-bottom: 1px solid #edf2f7; color: #1e293b; }
        .btn-delete { background: #fee2e2; color: #991b1b; border: none; padding: 4px 6px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-edit-inline { background: #e0f2fe; color: #0369a1; border: none; padding: 4px 6px; border-radius: 4px; cursor: pointer; font-weight: bold; margin-right: 2px; }
        .form-control { width: 100%; padding: 6px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.8rem; margin-bottom: 6px; outline: none; }
        
        .sidebar-toggle-btn { background: #059669; color: white; border: none; padding: 4px 8px; border-radius: 6px; cursor: pointer; font-size: 0.7rem; font-weight: bold; position: absolute; right: 0; top: -4px; }
        .sidebar-form-container { background: #f0fdf4; border: 1px solid #bbf7d0; padding: 10px; border-radius: 10px; margin-bottom: 14px; display: none; }
        .inline-edit-container { background: #f8fafc; border: 1px solid #cbd5e1; padding: 8px; border-radius: 8px; margin: 4px 0; display: none; width: 100%; }
        .btn-add-prod { width: 100%; padding: 8px; background: #059669; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.85rem; }
                .settings-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 9999; justify-content: center; align-items: center; }
        .settings-content { background: white; padding: 24px; border-radius: 16px; width: 90%; max-width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .settings-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; }
        .switch-input { width: 40px; height: 20px; cursor: pointer; }
        .btn-close-modal { width: 100%; padding: 10px; background: #cbd5e1; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 10px; }

        #printable-receipt-canvas { display: none; }
        @media print {
            @page { margin: 0; size: auto; }
            body * { display: none !important; }
            #printable-receipt-canvas, #printable-receipt-canvas * { display: block !important; }
            #printable-receipt-canvas { position: absolute; left: 0; top: 0; width: 58mm; padding: 3mm; font-family: 'Courier New', Courier, monospace !important; font-size: 12px !important; line-height: 1.3; color: black !important; background: white !important; }
            .rcpt-header { text-align: center; margin-bottom: 3mm; line-height: 1.2; }
            .rcpt-divider { border-top: 1px dashed black; margin: 2mm 0; width: 100%; }
            .rcpt-row { display: flex; justify-content: space-between; width: 100%; align-items: flex-start; }
            .rcpt-item-name { word-break: break-word; max-width: 70%; text-align: left; }
        }
        svg { width: 18px !important; height: 18px !important; fill: none; stroke: currentColor; stroke-width: 2; display: inline-block; vertical-align: middle; }
    </style>
</head>
<body>

    <nav class="store-nav">
        <div class="nav-brand">
            <svg style="color: #fef08a;" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            <span id="nav-store-name-display">Tindahan POS Lite</span>
        </div>
        <div style="display:flex; gap:12px; align-items:center;">
            <div id="net-profit-badge-wrapper" style="font-size: 0.85rem; font-weight:bold; background:#047857; padding:4px 12px; border-radius:6px; display:none;">Net Profit: ₱{{ number_format($netProfitMargin, 2) }}</div>
            <div style="font-size: 0.85rem; font-weight:bold; background:#b45309; padding:4px 12px; border-radius:6px;">Revenue: ₱{{ number_format($totalSalesAmount, 2) }}</div>
            
            <button onclick="openSettings()" style="background:transparent; border:none; color:white; cursor:pointer; padding:4px;">
                <svg style="width:22px!important; height:22px!important;" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </button>
            <div class="nav-badge">SQLite Active</div>
        </div>
    </nav>

    <div class="settings-overlay" id="settings-modal">
        <div class="settings-content">
            <h3 style="margin-top:0; border-bottom:2px solid #e2e8f0; padding-bottom:8px; text-align:center;">Display Panel Settings</h3>
            <div style="margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                <label class="form-label" style="display:block; font-size:0.8rem; font-weight:bold; color:#475569; margin-bottom:4px; text-transform:uppercase;">Store Name</label>
                <input type="text" id="store-name-input" class="form-control" style="font-size:0.9rem; padding:8px; width:100%; margin-bottom:0;" oninput="applyToggles()">
            </div>
            <div class="settings-row">
                <div><strong style="display:block; font-size:0.9rem;">Transaction History & Profit Logs</strong></div>
                <input type="checkbox" id="toggle-history-chk" class="switch-input" onchange="applyToggles()" checked>
            </div>
            <div class="settings-row">
                <div><strong style="display:block; font-size:0.9rem;">Utang (Credit Ledger)</strong></div>
                <input type="checkbox" id="toggle-utang-chk" class="switch-input" onchange="applyToggles()" checked>
            </div>
            <div class="settings-row">
                <div><strong style="display:block; font-size:0.9rem;">Auto-Print Receipts</strong></div>
                <input type="checkbox" id="toggle-printer-chk" class="switch-input" onchange="applyToggles()">
            </div>
            <button onclick="closeSettings()" class="btn-close-modal">✕ Close Dashboard</button>
        </div>
    </div>

    <div class="main-container">
        <div class="panel-block">
            <h2 class="panel-title">
                <svg style="color: #f59e0b;" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Available Items Assortment
            </h2>
            
            <div class="search-container-box">
                <div style="position:relative; flex-grow:1;">
                    <svg class="search-svg-icon" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" id="product-search-bar" class="search-input-field" placeholder="Mag-search ng aytem..." oninput="filterStoreItems()">
                </div>
                <input type="text" id="barcode-scan-catcher" class="barcode-quick-input" placeholder="[ Barcode Input ]" onkeydown="handleBarcodeScan(event)">
            </div>

                        <div class="scrollable-body">
                <div class="product-grid" id="shop-assortment-grid">
                    @foreach($products as $product)
                    <div class="store-item-card-wrapper" data-name="{{ strtolower($product->name) }}" data-cat="{{ strtolower($product->category) }}" data-barcode="{{ $product->barcode }}">
                        <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->retail_price }}, {{ $product->stock_quantity }})" class="product-button" {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                            <div class="prod-img-box" style="background-image: url('{{ $product->image_url }}'); background-size: cover; background-position: center;">
                                @if(!$product->image_url)
                                    <div class="prod-avatar-fallback">{{ substr($product->name, 0, 1) }}</div>
                                @endif
                            </div>
                            <div class="prod-details-content">
                                <div class="prod-cat">{{ $product->category }}</div>
                                <div class="prod-name">{{ $product->name }}</div>
                                <div class="prod-meta">
                                    <span class="prod-price">₱{{ number_format($product->retail_price, 2) }}</span>
                                    <span class="prod-qty">Qty: {{ $product->stock_quantity }}</span>
                                </div>
                            </div>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="panel-block panel-orange">
            <h2 class="panel-title" style="color: #ea580c;">
                <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Current Order Basket
            </h2>
            <div id="basket-container" class="scrollable-body">
                <div style="padding: 24px 0; color: #94a3b8; font-size: 0.8rem; text-align: center;">Basket empty. Click items.</div>
            </div>
            <div class="checkout-box">
                <div class="row-due">
                    <span style="font-size:0.85rem; font-weight:500;">Total Due</span>
                    <span class="val-due" id="total-amount-due">₱0.00</span>
                </div>
                <input type="number" id="cash-input" placeholder="Cash Rendered" class="input-cash" oninput="calculateChange()">
                <div class="change-box" id="change-display">
                    <span style="font-size:0.8rem;">Change:</span>
                    <span style="font-size: 1.15rem; font-weight: 900;" id="change-value">₱0.00</span>
                </div>
                <button id="submit-btn" onclick="submitCheckout()" class="btn-submit" style="margin-top:8px;" disabled>Complete Sale</button>
            </div>
        </div>

        <div class="panel-block panel-emerald">
            <div class="panel-title" style="color: #059669;">
                <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <span>Stock Sidebar</span>
                <button onclick="toggleStockForm()" class="sidebar-toggle-btn" id="toggle-form-btn">+ Add New</button>
            </div>
            <div class="sidebar-form-container" id="sidebar-stock-form">
                                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <input type="text" name="name" placeholder="Item Name" class="form-control" required>
                    <input type="text" name="category" placeholder="Category" class="form-control" required>
                    <!-- Dual Columns for Sale Price vs Wholesale Cost Tracking -->
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:6px;">
                        <input type="number" step="0.01" name="retail_price" placeholder="Retail Price (₱)" class="form-control" required>
                        <input type="number" step="0.01" name="cost_price" placeholder="Capital Cost (₱)" class="form-control" required>
                    </div>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:6px;">
                        <input type="number" name="stock_quantity" placeholder="Initial Qty" class="form-control" required>
                        <input type="text" name="barcode" placeholder="Barcode Number" class="form-control">
                    </div>
                    <input type="text" name="image_url" placeholder="Photo link URL" class="form-control">
                    <button type="submit" class="btn-add-prod">+ Save New Item</button>
                </form>

            </div>

                        <div class="scrollable-body">
                <table class="mgmt-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Stock</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $prod)
                        <tr>
                            <td style="font-weight:500; font-size:0.75rem;">{{ $prod->name }}</td>
                            <td style="font-weight:bold; text-align:center;">{{ $prod->stock_quantity }}</td>
                            <td style="text-align:right; white-space:nowrap;">
                                <button onclick="toggleInlineEditForm({{ $prod->id }})" class="btn-edit-inline">Edit</button>
                                <form action="{{ route('products.destroy', $prod->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <!-- Old text was just a confusing 'X'. Let's make it clear it means deleting the whole product profile -->
<button type="submit" class="btn-delete" style="font-size: 0.7rem; padding: 4px 6px;">Delete Item Profile</button>

                                </form>
                            </td>
                        </tr>
                        
                                               <tr id="inline-edit-row-{{ $prod->id }}" class="inline-edit-container">
                            <td colspan="3" style="padding: 10px; background-color:#f1f5f9; border-radius:8px;">
                                <form action="{{ route('products.update', $prod->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <label style="font-size:0.65rem; font-weight:bold; text-transform:uppercase; display:block; margin-bottom:2px; color:#475569;">Edit Product Details</label>
                                    <input type="text" name="name" value="{{ $prod->name }}" class="form-control" required>
                                    <input type="text" name="category" value="{{ $prod->category }}" class="form-control" required>
                                    <!-- Interactive Cost Adjusters row -->
                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px;">
                                        <div>
                                            <label style="font-size:0.65rem; font-weight:bold; display:block; margin-bottom:2px; color:#475569;">Retail Price (₱)</label>
                                            <input type="number" step="0.01" name="retail_price" value="{{ $prod->retail_price }}" class="form-control" required>
                                        </div>
                                        <div>
                                            <label style="font-size:0.65rem; font-weight:bold; display:block; margin-bottom:2px; color:#475569;">Capital Cost (₱)</label>
                                            <input type="number" step="0.01" name="cost_price" value="{{ $prod->cost_price }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px;">
                                        <input type="number" name="stock_quantity" value="{{ $prod->stock_quantity }}" class="form-control" required>
                                        <input type="text" name="barcode" value="{{ $prod->barcode }}" class="form-control" placeholder="Barcode ID">
                                    </div>
                                    <input type="text" name="image_url" value="{{ $prod->image_url }}" class="form-control" placeholder="Picture Address">
                                    <button type="submit" class="btn-add-prod" style="padding:6px; margin-top:4px;">Save Product Changes</button>
                                </form>
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="secondary-grid">
        <div class="panel-block" id="history-panel-block" style="height:320px;">
            <h2 class="panel-title" style="color: #ea580c;">
                <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Sales & Net Profit Ledger Tracker
            </h2>
            <div class="scrollable-body">
                <table class="mgmt-table">
                    <thead>
                        <tr>
                            <th>Receipt ID</th>
                            <th>Revenue Collected</th>
                            <th>Cash Paid</th>
                            <th>Change Given</th>
                            <th>Date/Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesHistory as $sale)
                        <tr>
                            <td><strong>#{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                            <td style="color:#047857; font-weight:bold;">₱{{ number_format($sale->total_amount, 2) }}</td>
                            <td>₱{{ number_format($sale->amount_paid, 2) }}</td>
                            <td>₱{{ number_format($sale->change, 2) }}</td>
                            <td style="font-size:0.72rem; color:#64748b;">{{ $sale->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:#94a3b8; padding:20px;">No sales logged today.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

                <div class="panel-block panel-purple" id="utang-panel-block" style="height:320px;">
            <h2 class="panel-title" style="color: #a855f7;">
                <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Utang (Credit Ledger System)
            </h2>
            <form action="{{ route('credits.store') }}" method="POST" style="display:flex; gap:6px; margin-bottom:12px; flex-shrink:0;">
                @csrf
                <input type="text" name="customer_name" placeholder="Debtor Name" class="form-control" style="margin-bottom:0;" required>
                <input type="number" step="0.01" name="amount" placeholder="Debt (₱)" class="form-control" style="margin-bottom:0; max-width:100px;" required>
                <button type="submit" class="btn-add-prod" style="background:#a855f7; max-width:80px; padding:4px;">+ Log</button>
            </form>
            <div class="scrollable-body">
                <table class="mgmt-table">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Amount Due</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($credits as $credit)
                        <tr>
                            <td><strong>{{ $credit->customer_name }}</strong></td>
                            <td style="font-weight:bold; color:#b91c1c;">₱{{ number_format($credit->amount, 2) }}</td>
                            <td>
                                <form action="{{ url('/credits/'.$credit->id.'/toggle') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="cursor:pointer; font-weight:bold; border:none; padding:2px 6px; border-radius:4px; font-size:0.7rem; {{ $credit->status === 'Paid' ? 'background:#d1fae5; color:#065f46;' : 'background:#fee2e2; color:#991b1b;' }}">
                                        {{ $credit->status }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('credits.destroy', $credit->id) }}" method="POST" onsubmit="return confirm('Clear debtor history line?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:#94a3b8; padding:20px;">No credit accounts active.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/pos-engine.js') }}"></script>
</body>
</html>
