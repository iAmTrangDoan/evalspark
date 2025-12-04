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
        Schema::create('score', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            
            // user_id: Sinh viên/Nhóm trưởng được chấm điểm
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict'); 
            
            // lecture_id: Giảng viên chấm điểm
            $table->foreignId('lecture_id')->constrained('users')->onDelete('restrict');
            
            $table->decimal('score', 4, 2)->nullable(); // score (Ví dụ: 8.50)
            $table->text('comment')->nullable();
            
            // created_date tương đương với created_at
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score');
    }
};
