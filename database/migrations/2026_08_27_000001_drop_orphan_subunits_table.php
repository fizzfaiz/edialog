<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove the orphaned `subunits` table (created by a deleted migration).
     * Sub-units are now modelled as regular `units` under a `sektor`.
     */
    public function up(): void
    {
        // 1. Drop the foreign key + column that references subunits.
        if (Schema::hasTable('takwims') && Schema::hasColumn('takwims', 'subunit_id')) {
            Schema::table('takwims', function (Blueprint $table) {
                $table->dropConstrainedForeignId('subunit_id');
            });
        }

        // 2. Drop the orphaned subunits table.
        Schema::dropIfExists('subunits');
    }

    /**
     * Best-effort rollback (recreates the legacy table shape).
     */
    public function down(): void
    {
        if (!Schema::hasTable('subunits')) {
            Schema::create('subunits', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary();
                $table->string('name');
                $table->timestamps();
            });
        }

        if (Schema::hasTable('takwims') && !Schema::hasColumn('takwims', 'subunit_id')) {
            Schema::table('takwims', function (Blueprint $table) {
                $table->foreignId('subunit_id')->nullable()->constrained('subunits')->cascadeOnDelete();
            });
        }
    }
};
