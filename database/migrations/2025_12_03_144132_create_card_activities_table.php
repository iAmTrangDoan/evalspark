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
        Schema::create('card_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('cards')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            
            $table->string('action'); // Ví dụ: 'moved', 'archived', 'commented'
            $table->text('activity'); // Chi tiết hoạt động (ví dụ: 'moved from To Do to Doing')
            
            // timestamp tương đương với created_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_activities');
    }
};
