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
        if (! Schema::hasTable('home_sections')) {
            Schema::create('home_sections', function (Blueprint $table) {
                $table->id();
                $table->string('section_key', 100)->unique();
                $table->string('section_group', 50);
                $table->string('label', 100);
                $table->text('content');
                $table->string('highlight_word', 50)->nullable();
                $table->unsignedInteger('char_limit')->default(100);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_sections');
    }
};
