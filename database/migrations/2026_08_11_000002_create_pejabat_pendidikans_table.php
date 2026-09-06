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
        Schema::create('pejabat_pendidikans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kod')->unique();
            $table->enum('jenis', ['kpm', 'jpn', 'ppd']);
            $table->foreignId('induk_id')->nullable()->constrained('pejabat_pendidikans')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pejabat_pendidikans');
    }
};