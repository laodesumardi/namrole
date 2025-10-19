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
        Schema::table('users', function (Blueprint $table) {
            // Drop columns only if they exist to avoid migration failure
            if (Schema::hasColumn('users', 'is_admin')) {
                $table->dropColumn('is_admin');
            }
            if (Schema::hasColumn('users', 'is_teacher')) {
                $table->dropColumn('is_teacher');
            }
            if (Schema::hasColumn('users', 'is_student')) {
                $table->dropColumn('is_student');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_teacher')->default(false);
            $table->boolean('is_student')->default(true);
        });
    }
};
