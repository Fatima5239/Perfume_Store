<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_stats', function (Blueprint $table) {
            $table->id();
            $table->string('search_query');
            $table->integer('results_count')->default(0);
            $table->string('session_id');
            $table->string('ip_address', 45);
            $table->timestamps();
            
            $table->index(['search_query', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_stats');
    }
};