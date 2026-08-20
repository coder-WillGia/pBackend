<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index('created_at', 'idx_products_created_at');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('created_at', 'idx_categories_created_at');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_created_at');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_created_at');
        });
    }
};
