<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dp_ppd_reports', function (Blueprint $table) {
            $table->id();
            $table->string('pengerusi');
            $table->date('tarikh');
            $table->string('hari');
            $table->time('masa');
            $table->string('tempat');
            $table->string('dicatat_oleh');
            $table->string('jawatan_pencatat');
            $table->string('disahkan_oleh');
            $table->string('jawatan_pengesah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dp_ppd_reports');
    }
};
