<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('payment_type'); // DP, Pelunasan, Cicilan
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->string('proof_image')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi (Rollback).
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};