<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_stats', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->string('page_url');
            $table->string('page_type'); // 'home', 'collection', 'product', 'search'
            $table->enum('device', ['desktop', 'mobile', 'tablet'])->default('desktop');
            $table->timestamp('visited_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_stats');
    }
};