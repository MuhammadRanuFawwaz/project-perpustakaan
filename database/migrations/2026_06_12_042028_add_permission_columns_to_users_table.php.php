<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'can_edit_peminjaman')) {
                $table->boolean('can_edit_peminjaman')->default(false)->after('role');
            }

            if (!Schema::hasColumn('users', 'can_delete_peminjaman')) {
                $table->boolean('can_delete_peminjaman')->default(false)->after('can_edit_peminjaman');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'can_delete_peminjaman')) {
                $table->dropColumn('can_delete_peminjaman');
            }

            if (Schema::hasColumn('users', 'can_edit_peminjaman')) {
                $table->dropColumn('can_edit_peminjaman');
            }
        });
    }
};
