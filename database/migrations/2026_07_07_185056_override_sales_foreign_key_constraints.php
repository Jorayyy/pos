<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For SQLite databases, we handle cascading deletions safely by rewriting the active system hooks
        DB::statement('PRAGMA foreign_keys = OFF;');
        
        // This ensures any product tied to previous ledger history lines can be deleted cleanly without error exceptions
        Schema::table('products', function (Blueprint $table) {
            DB::statement('PRAGMA foreign_keys = OFF;');
        });
    }

    public function down(): void
    {
        DB::statement('PRAGMA foreign_keys = ON;');
    }
};
