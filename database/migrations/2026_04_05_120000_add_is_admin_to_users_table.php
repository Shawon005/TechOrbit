<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->boolean('is_admin')->default(false)->after('password');
            });
        }

        User::query()->updateOrCreate(
            ['email' => 'admin@techorbitit.com'],
            [
                'name' => 'TechOrbit Admin',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );
    }

    public function down(): void
    {
        User::query()->where('email', 'admin@techorbitit.com')->delete();

        if (Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('is_admin');
            });
        }
    }
};
