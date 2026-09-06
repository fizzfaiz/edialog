<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dp_ppd_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('dp_ppd_reports')->cascadeOnDelete();
            $table->string('nama');
            $table->string('jawatan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dp_ppd_attendances');
    }
};
