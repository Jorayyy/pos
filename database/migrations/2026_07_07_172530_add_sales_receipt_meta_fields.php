<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('sales', function (Blueprint $table) {
        // We will store the receipt breakdown as a lightweight text column
        $table->text('items_json')->nullable(); 
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
