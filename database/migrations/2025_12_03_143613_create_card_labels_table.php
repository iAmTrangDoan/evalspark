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
        Schema::create('card_labels', function (Blueprint $table) {
            $table->foreignId('label_id')->constrained('labels')->onDelete('cascade');
            $table->foreignId('card_id')->constrained('cards')->onDelete('cascade');
            
            // Khóa chính tổng hợp
            $table->primary(['label_id', 'card_id']); 
            
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_labels');
    }
};
