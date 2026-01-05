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
        if (!Schema::hasColumn('cards', 'priority')) {
            Schema::table('cards', function (Blueprint $table) {
                $table->tinyInteger('priority')->default(0)->comment('0:None, 1:High, 2:Medium, 3:Low');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            //
        });
    }
};
