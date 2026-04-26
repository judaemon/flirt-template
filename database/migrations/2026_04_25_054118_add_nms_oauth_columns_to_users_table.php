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
            $table->string('hash', 16)->nullable()->index()->after('id');
            $table->unsignedBigInteger('user_account_id')->nullable()->unique()->after('hash');
            $table->string('last_name', 255)->index()->nullable()->after('operator_code');
            $table->string('first_name', 255)->index()->nullable()->after('last_name');
            $table->string('middle_name', 255)->index()->nullable()->after('first_name');
            $table->string('suffix', 5)->index()->nullable()->after('middle_name');
            $table->string('personal_email', 255)->nullable()->unique()->after('email');
            $table->string('company_email', 255)->index()->nullable()->after('personal_email');
            $table->string('status', 255)->index()->nullable()->after('company_email');
            $table->string('md5_personal_email', 255)->unique()->nullable()->after('status');
            $table->string('md5_company_email', 255)->index()->nullable()->after('md5_personal_email');
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'hash',
                'user_account_id',
                'last_name',
                'first_name',
                'middle_name',
                'suffix',
                'personal_email',
                'company_email',
                'status',
                'md5_personal_email',
                'md5_company_email',
            ]);
            $table->string('password')->nullable(false)->change();
        });
    }
};
