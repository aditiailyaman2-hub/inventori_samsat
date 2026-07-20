<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->unsignedInteger('jumlah_keluar');
            $table->date('tanggal_keluar');
            $table->string('penerima');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['item_id', 'tanggal_keluar']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_outs');
    }
};

