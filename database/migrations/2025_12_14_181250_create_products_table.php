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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('fragrance_notes')->nullable();
        $table->enum('gender', ['men', 'women', 'unisex']);
        $table->decimal('price_100ml', 10, 2); // Standard 100ml price
        $table->decimal('discount_100ml', 10, 2)->nullable(); // Discount for 100ml
        $table->boolean('available')->default(true); 
        $table->string('main_image')->nullable();
        $table->enum('status', ['active', 'hidden'])->default('active');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
