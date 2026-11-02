<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('seeker')->after('email'); // admin | employer | seeker
            $table->string('company_name')->nullable()->after('role');
            $table->string('phone', 20)->nullable()->after('company_name');
            $table->string('resume_path')->nullable()->after('phone');   // seeker's default resume
            $table->boolean('is_blocked')->default(false)->after('resume_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'company_name', 'phone', 'resume_path', 'is_blocked']);
        });
    }
};
