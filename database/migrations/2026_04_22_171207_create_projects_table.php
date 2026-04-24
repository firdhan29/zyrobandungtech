<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('client_name');
            $table->string('client_phone');
            $table->string('client_email');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('dp_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['planning', 'development', 'testing', 'completed', 'on_hold'])->default('planning');
            $table->text('progress_notes')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->string('whatsapp_link')->nullable();
            $table->timestamps();
        });
    }
};