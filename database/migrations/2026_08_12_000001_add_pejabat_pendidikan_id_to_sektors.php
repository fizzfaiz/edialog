<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('sektors', 'pejabat_pendidikan_id')) {
            Schema::table('sektors', function (Blueprint $table) {
                $table->foreignId('pejabat_pendidikan_id')
                    ->nullable()
                    ->constrained('pejabat_pendidikans')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sektors', 'pejabat_pendidikan_id')) {
            Schema::table('sektors', function (Blueprint $table) {
                $table->dropConstrainedForeignId('pejabat_pendidikan_id');
            });
        }
    }
};