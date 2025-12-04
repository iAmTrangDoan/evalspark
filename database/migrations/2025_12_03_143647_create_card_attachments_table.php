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
        Schema::create('card_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('cards')->onDelete('cascade');
            
            $table->string('file_name');
            $table->string('url'); // Đường dẫn lưu trữ tệp
            
            // uploader_date tương đương với created_at
            $table->timestamp('uploaded_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_attachments');
    }
};
