<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL only: the ALTER ... AUTO_INCREMENT syntax is not supported by SQLite.
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $tableNames = config('permission.table_names');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Fix auto-increment on permissions table
        if (Schema::hasTable($tableNames['permissions'])) {
            DB::statement('ALTER TABLE `' . $tableNames['permissions'] . '` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        // Fix auto-increment on roles table
        if (Schema::hasTable($tableNames['roles'])) {
            DB::statement('ALTER TABLE `' . $tableNames['roles'] . '` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        // Fix auto-increment on migrations table itself
        if (Schema::hasTable('migrations')) {
            DB::statement('ALTER TABLE `migrations` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse — bigIncrements should have been applied originally
    }
};