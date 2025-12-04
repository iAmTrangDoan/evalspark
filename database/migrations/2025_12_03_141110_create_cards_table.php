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
        Schema::create('cards', function (Blueprint $table) {
           $table->id(); 

            $table->foreignId('list_id')->constrained('lists')->onDelete('cascade'); 
            // Xóa Card nếu List bị xóa
            
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('priority')->default(0); // Ưu tiên
            $table->unsignedInteger('position'); // Vị trí (Position) để sắp xếp Card
            
            $table->timestamp('due_date')->nullable(); // Ngày hết hạn
            $table->timestamp('reminder_date')->nullable(); // Ngày nhắc nhở
            
            $table->boolean('is_completed')->default(false); // Đã hoàn thành
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->timestamp('created_at')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
