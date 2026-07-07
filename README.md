# 🛒 Tindahan POS Lite

A smart, lightweight, and responsive Point of Sale (POS) system custom-tailored for local Sari-Sari stores and micro-retail businesses. Built on top of Laravel, SQLite, and clean vanilla JavaScript, it runs fast offline and offers real-time item filtering, custom product profiling, debt logs, and automated profit math analytics.

---

## ✨ Features Engine Matrix

### 1. Frontline Shop Assortment Grid
* **Visual Asset Management:** Vibrant product cards displaying product categories, retail values, remaining stock balances, and high-quality image previews.
* **Auto-Avatar Fallbacks:** Items without internet picture addresses automatically generate clean, letter-themed placeholder circles so the store display layout never breaks.

### 2. Live Keyword & Barcode Scanner Integration
* **Real-time Filtering:** A quick-search bar dynamically hides or displays products by name or category as you type characters.
* **Barcode Scan Listener Hook:** A dedicated green `[ Barcode Input ]` field automatically captures hardware laser scanner sweeps or typed barcode sequences (e.g., `480002233445`), instantly dropping matched items into the order counter basket.

### 3. Current Order Basket & Checkout Mechanics
* **Interactive Counter Basket:** Dynamic checkout rows that let you increase, decrease, or remove selected quantities instantly. 
* **Live Cash Metrics:** The system automatically locks the "Complete Sale" trigger until the cash rendered matches or exceeds the total amount due, calculating change values on the fly.

### 4. Optional Operations Ledgers System
* **Display Toggle Modal:** Click the orange **Settings Gear icon (⚙️)** in the top bar to pull up configuration switches that allow you to dynamically hide or show the lower data panels.
* **Utang (Credit Ledger System):** Log store credits instantly under specific debtor profile names with real-time status toggle triggers ("Paid" vs "Unpaid").
* **Sales History Log Matrix:** Tracks past successful transactions, pulling in automatic, localized Manila timestamps, receipt sequence codes, and total amounts.
* **Dynamic Net Profit Tracker:** Calculates absolute wholesale capital costs (`cost_price`) against retail collections (`retail_price`), providing the store operator with an explicit, real-time green **Net Profit Badge** display.

### 5. Standard 58mm Thermal Print Directives
* **Optional Auto-Print:** Turn on auto-printing inside your Settings panel to launch the browser's native print prompt window immediately upon clicking "Complete Sale".
* **Narrow Roll Sheet Layout:** Formats transaction receipts to a continuous paper roll layout using clean, high-contrast monospace Courier text, item subtotal structures, and local shop name strings.

---

## 🛠️ Installation & Setup Blueprint

### 1. Environment Configurations
Clone your project directory or enter your repository folder path using your terminal:
```powershell
cd tindahan-pos
```
Create your local environment properties file by copying the template setup:
```powershell
cp .env.example .env
```
Open your `.env` configuration file and adjust your database connection rules to target SQLite directly:
```env
DB_CONNECTION=sqlite
# Delete or comment out other DB lines (DB_HOST, DB_PORT, DB_DATABASE, etc.)
```

### 2. Initialize Database File
Create your blank local storage SQLite file inside your project framework tree:
```powershell
New-Item -ItemType File -Path database/database.sqlite -Force
```
Generate your framework security app key signature:
```powershell
php artisan key:generate
```

### 3. Run Table Schema Migrations
Execute your database alteration scripts to install your columns for barcodes, image URLs, sales JSON metadata logs, and wholesale cost prices:
```powershell
php artisan migrate
```

### 4. Build Framework Cache Structures & Launch Server
Flush out any residual view logs to force Laravel's rendering engine to compile fresh components:
```powershell
php artisan view:clear
```
Boot your local PHP engine server pipeline:
```powershell
php artisan serve
```
Open your web browser window and navigate to your active app link: **`http://127.0.0.1:8000`**

---

## 🚀 Daily Store Operational Guide

### 1. Registering or Editing Store Inventory
* To add new products, click **`+ Add New`** inside the green stock sidebar column. Input your item name, category, sales retail price, wholesale capital cost, remaining stock count, and barcode.
* To add an item photo, **right-click** any product picture on a web page, choose **`Copy image address`** (ensure the path points directly to an image asset, e.g., ending in `.png`, `.jpg`, or `.webp`), paste it into the URL field, and hit save.
* Use the blue **`Edit`** rows in the sidebar to adjust wholesale prices, replace pictures, or fix barcode values.

### 2. Running a Customer Checkout Transaction
* Click on items in the left menu grid, or focus your cursor inside the green **`[ Barcode Input ]`** box and scan/type an item's barcode numbers and hit **`Enter`** to drop it into the cart.
* In the center checkout basket, adjust quantities using the `+` or `-` triggers.
* Enter the cash amount provided by the customer into the **`Cash Rendered`** field.
* Click **`Complete Sale`**. If receipt printing is active inside your configuration toggles, your browser's printer options page will drop down instantly.

### 3. Monitoring Store Profit Margins
* Keep your **Sales History & Profit Logs** checked active inside your Settings panel.
* As sales get logged, look at the green **`Net Profit`** badge in the top navbar header. It automatically subtracts your wholesale capital costs from retail cash takings to display your true, exact store earnings for the day!
