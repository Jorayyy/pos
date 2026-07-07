let cart = [];

// Dynamic Barcode Keypress Scan Integration Engine Handler
function handleBarcodeScan(event) {
    if (event.key === 'Enter') {
        let code = event.target.value.trim();
        if (!code) return;
        
        let matchWrapper = document.querySelector('[data-barcode="' + code + '"]');
        if (matchWrapper) {
            let btn = matchWrapper.querySelector('.product-button');
            if (btn && !btn.disabled) {
                btn.click(); // Trigger automatic button selection click event
                event.target.value = ''; // Reset hardware input buffer string registers
                return;
            }
        }
        alert('Barcode target code not matched in database rows.');
        event.target.value = '';
    }
}

// Inline Row Form Visibility Controller logic
function toggleInlineEditForm(id) {
    let row = document.getElementById('inline-edit-row-' + id);
    if (row && row.style.display === 'table-row') {
        row.style.display = 'none';
    } else {
        let forms = document.getElementsByClassName('inline-edit-container');
        for (let i = 0; i < forms.length; i++) forms[i].style.display = 'none';
        if (row) row.style.display = 'table-row';
    }
}

// Live Filtering Real-time Search Box Handler Function
function filterStoreItems() {
    let query = document.getElementById('product-search-bar').value.toLowerCase().trim();
    let cards = document.getElementsByClassName('store-item-card-wrapper');
    for (let i = 0; i < cards.length; i++) {
        let nameAttr = cards[i].getAttribute('data-name') || '';
        let catAttr = cards[i].getAttribute('data-cat') || '';
        if (nameAttr.includes(query) || catAttr.includes(query)) {
            cards[i].style.display = 'block';
        } else {
            cards[i].style.display = 'none';
        }
    }
}

// Initialize boot parameters out of persistent LocalStorage sandboxes
document.addEventListener("DOMContentLoaded", function() {
    let savedName = localStorage.getItem("storeName") || "Tindahan POS Lite";
    let historyState = localStorage.getItem("showHistory") !== "false";
    let utangState = localStorage.getItem("showUtang") !== "false";
    let printerState = localStorage.getItem("autoPrint") === "true";

    let nameInput = document.getElementById('store-name-input');
    let histChk = document.getElementById('toggle-history-chk');
    let utangChk = document.getElementById('toggle-utang-chk');
    let printChk = document.getElementById('toggle-printer-chk');

    if (nameInput) nameInput.value = savedName;
    if (histChk) histChk.checked = historyState;
    if (utangChk) utangChk.checked = utangState;
    if (printChk) printChk.checked = printerState;

    applyToggles();
    let scannerInput = document.getElementById('barcode-scan-catcher');
    if (scannerInput) scannerInput.focus(); // Autofocus instantly onto hardware reader box
});

function openSettings() { 
    let modal = document.getElementById('settings-modal');
    if (modal) modal.style.display = 'flex'; 
}

function closeSettings() { 
    let modal = document.getElementById('settings-modal');
    if (modal) modal.style.display = 'none'; 
}

function applyToggles() {
    let nameInput = document.getElementById('store-name-input');
    let histChk = document.getElementById('toggle-history-chk');
    let utangChk = document.getElementById('toggle-utang-chk');
    
    let storeName = nameInput ? nameInput.value.trim() : "Tindahan POS Lite";
    if (!storeName) storeName = "Tindahan POS Lite";
    
    let showHistory = histChk ? histChk.checked : true;
    let showUtang = utangChk ? utangChk.checked : true;
    
    localStorage.setItem("storeName", storeName);
    localStorage.setItem("showHistory", showHistory);
    localStorage.setItem("showUtang", showUtang);

    let navDisplay = document.getElementById('nav-store-name-display');
    let histPanel = document.getElementById('history-panel-block');
    let utangPanel = document.getElementById('utang-panel-block');
    let profitBadge = document.getElementById('net-profit-badge-wrapper');

    if (navDisplay) navDisplay.innerText = storeName;
    if (histPanel) histPanel.style.display = showHistory ? 'flex' : 'none';
    if (utangPanel) utangPanel.style.display = showUtang ? 'flex' : 'none';
    if (profitBadge) profitBadge.style.display = showHistory ? 'block' : 'none';
}

function toggleStockForm() {
    let form = document.getElementById('sidebar-stock-form');
    let btn = document.getElementById('toggle-form-btn');
    if (form && form.style.display === 'block') {
        form.style.display = 'none';
        if (btn) btn.innerText = '+ Add New';
    } else {
        if (form) form.style.display = 'block';
        if (btn) btn.innerText = '✕ Close';
    }
}

function addToCart(id, name, price, maxStock) {
    let found = cart.find(item => item.id === id);
    if (found) {
        if (found.quantity < maxStock) found.quantity++;
    } else {
        cart.push({ id: id, name: name, price: parseFloat(price), quantity: 1, maxStock: parseInt(maxStock) });
    }
    renderCart();
}

function updateQty(id, change) {
    let found = cart.find(item => item.id === id);
    if (!found) return;
    found.quantity += change;
    if (found.quantity > found.maxStock) found.quantity = found.maxStock;
    if (found.quantity <= 0) cart = cart.filter(item => item.id !== id);
    renderCart();
}

function getTotal() { 
    return cart.reduce((sum, item) => sum + (item.price * item.quantity), 0); 
}

function renderCart() {
    let container = document.getElementById('basket-container');
    if (!container) return;
    
    if (cart.length === 0) {
        container.innerHTML = '<div style="padding: 24px 0; color: #94a3b8; font-size: 0.8rem; text-align: center;">Basket empty. Click items.</div>';
        let dueLabel = document.getElementById('total-amount-due');
        if (dueLabel) dueLabel.innerText = '₱0.00';
        calculateChange();
        return;
    }

    let html = '';
    cart.forEach(item => {
        html += '<div class="basket-item">' +
            '<div style="flex: 1; padding-right: 4px;">' +
                '<span style="font-weight: bold; color: #1e293b; font-size: 0.8rem; display:block; line-height:1.2;">' + item.name + '</span>' +
                '<span style="font-size: 0.7rem; color: #64748b;">₱' + item.price.toFixed(2) + '</span>' +
            '</div>' +
            '<div style="display:flex; align-items:center; gap:6px;">' +
                '<div class="qty-controls">' +
                    '<button onclick="updateQty(' + item.id + ', -1)" class="qty-btn">-</button>' +
                    '<span class="qty-val">' + item.quantity + '</span>' +
                    '<button onclick="updateQty(' + item.id + ', 1)" class="qty-btn">+</button>' +
                '</div>' +
                '<span style="font-weight: bold; color: #0f172a; font-size:0.8rem; width: 45px; text-align: right;">₱' + (item.price * item.quantity).toFixed(2) + '</span>' +
            '</div>' +
        '</div>';
    });
    container.innerHTML = html;
    let dueLabel = document.getElementById('total-amount-due');
    if (dueLabel) dueLabel.innerText = '₱' + getTotal().toFixed(2);
    calculateChange();
}

function calculateChange() {
    let total = getTotal();
    let cashField = document.getElementById('cash-input');
    let cashInput = cashField ? cashField.value : '';
    let cash = parseFloat(cashInput) || 0;
    
    let changeBox = document.getElementById('change-display');
    let submitBtn = document.getElementById('submit-btn');
    let changeVal = document.getElementById('change-value');

    if (cart.length > 0 && cash >= total && cashInput !== '') {
        let change = cash - total;
        if (changeVal) changeVal.innerText = '₱' + change.toFixed(2);
        if (changeBox) changeBox.style.display = 'flex';
        if (submitBtn) submitBtn.disabled = false;
    } else {
        if (changeBox) changeBox.style.display = 'none';
        if (submitBtn) submitBtn.disabled = true;
    }
}

async function submitCheckout() {
    let cashField = document.getElementById('cash-input');
    let cashVal = cashField ? parseFloat(cashField.value) : 0;
    let csrfToken = document.querySelector('input[name="_token"]');
    let tokenVal = csrfToken ? csrfToken.value : '';

    let response = await fetch('/checkout', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': tokenVal },
        body: JSON.stringify({ items: cart, amount_paid: cashVal })
    });
    let result = await response.json();
    if (result.success) {
        let printChk = document.getElementById('toggle-printer-chk');
        let printEnabled = printChk ? printChk.checked : false;
        
        if (printEnabled) {
            let currentStoreName = localStorage.getItem("storeName") || "TINDahan POS LITE";
            let receiptCanvas = document.getElementById('printable-receipt-canvas');
            if (receiptCanvas) {
                receiptCanvas.innerHTML = ''; 

                let headerNode = document.createElement('div'); headerNode.className = 'rcpt-header';
                headerNode.innerText = currentStoreName.toUpperCase() + '\nOfficial Receipt';
                receiptCanvas.appendChild(headerNode);

                let metaNode = document.createElement('div');
                metaNode.innerText = 'OR #: ' + result.sale_id + '\nDate: ' + result.timestamp;
                receiptCanvas.appendChild(metaNode);

                let div1 = document.createElement('div'); div1.className = 'rcpt-divider'; receiptCanvas.appendChild(div1);

                result.items.forEach(i => {
                    let row = document.createElement('div'); row.className = 'rcpt-row';
                    row.innerHTML = '<span class="rcpt-item-name">' + i.name + '<br>  ' + i.quantity + ' x ₱' + i.price.toFixed(2) + '</span><span>₱' + i.subtotal.toFixed(2) + '</span>';
                    receiptCanvas.appendChild(row);
                });

                let div2 = document.createElement('div'); div2.className = 'rcpt-divider'; receiptCanvas.appendChild(div2);

                let totalRow = document.createElement('div'); totalRow.className = 'rcpt-row'; totalRow.style.fontWeight = 'bold';
                totalRow.innerHTML = '<span>TOTAL:</span><span>₱' + result.total.toFixed(2) + '</span>'; receiptCanvas.appendChild(totalRow);

                let paidRow = document.createElement('div'); paidRow.className = 'rcpt-row';
                paidRow.innerHTML = '<span>Cash Paid:</span><span>₱' + result.paid.toFixed(2) + '</span>'; receiptCanvas.appendChild(paidRow);

                let changeRow = document.createElement('div'); changeRow.className = 'rcpt-row'; changeRow.style.fontWeight = 'bold';
                changeRow.innerHTML = '<span>CHANGE:</span><span>₱' + result.change.toFixed(2) + '</span>'; receiptCanvas.appendChild(changeRow);

                let div3 = document.createElement('div'); div3.className = 'rcpt-divider'; receiptCanvas.appendChild(div3);

                let footerNode = document.createElement('div'); footerNode.style.textAlign = 'center'; footerNode.style.fontSize = '10px'; footerNode.style.marginTop = '3mm';
                footerNode.innerText = 'Salamat po! Come Again! ❤️'; receiptCanvas.appendChild(footerNode);
                window.print();
            }
        } else {
            alert('Transaction Complete! Change: ₱' + result.change.toFixed(2));
        }
        window.location.reload();
    } else {
        alert(result.error || 'Checkout process failed.');
    }
}
