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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Các khóa ngoại có thể NULL vì thông báo có thể không liên quan đến Card hoặc Group cụ thể
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('set null');
            $table->foreignId('card_id')->nullable()->constrained('cards')->onDelete('set null');
            $table->string('type'); //loại thông báo : 'comment', 'due_date', 'added_to_card', score_updated,card_moved,etc.)
            $table->text('nofitication'); // Nội dung thông báo
            $table->boolean('is_read')->default(false); // Đã đọc
            
            // created_date tương đương với created_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
