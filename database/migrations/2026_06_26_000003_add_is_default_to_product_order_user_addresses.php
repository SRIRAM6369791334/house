<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_addresses') && ! Schema::hasColumn('user_addresses', 'is_default')) {
            Schema::table('user_addresses', function (Blueprint $table) {
                $table->boolean('is_default')->default(false)->after('address_type_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('user_addresses') && Schema::hasColumn('user_addresses', 'is_default')) {
            Schema::table('user_addresses', function (Blueprint $table) {
                $table->dropColumn('is_default');
            });
        }
    }
};
