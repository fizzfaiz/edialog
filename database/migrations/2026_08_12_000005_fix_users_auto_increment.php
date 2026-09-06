<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL only: the ALTER ... AUTO_INCREMENT syntax is not supported by SQLite.
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('ALTER TABLE `users` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // No reverse needed
    }
};