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
        Schema::create('board_member_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->integer('total_completed_cards')->default(0);
            $table->integer('total_completed_checklist_items')->default(0);
            $table->integer('total_uncompleted_cards')->default(0);
            $table->integer('total_uncompleted_checklist_items')->default(0);
            $table->decimal('contribution', 5, 2)->nullable(); // Tỷ lệ đóng góp (ví dụ: 0.25 = 25%)
            
            $table->timestamp('last_updated')->nullable();
            // $table->timestamps(); // Sẽ tạo thêm created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_member_stats');
    }
};
