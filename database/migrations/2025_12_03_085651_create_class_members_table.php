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
        Schema::create('class_members', function (Blueprint $table) {
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade'); 
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Đặt khóa chính tổng hợp (Composite Primary Key)
            $table->primary(['class_id', 'user_id']); 
            
            $table->timestamp('joined_date')->useCurrent(); // joined_date
            
            // role (Phân quyền trong lớp học, vd: 'student', 'ta', 'guest')
            // Vai trò này có thể khác với role chung của users (lecturer/student)
            $table->string('role')->default('student'); 


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_members');
    }
};
