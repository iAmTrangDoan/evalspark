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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecture_id')->constrained('users')->onDelete('cascade');            
            $table->string('name'); // name
            $table->string('join_code')->unique(); // join_code (Mã tham gia lớp học)
            $table->string('semester')->nullable(); // semester
            $table->text('description')->nullable(); // description
            $table->string('status')->default('active'); // status ('active', 'archived', etc.)
            $table->timestamp('created_at')->nullable();//created_date
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
