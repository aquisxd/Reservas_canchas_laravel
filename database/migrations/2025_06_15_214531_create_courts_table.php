<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourtsTable extends Migration
{
    public function up(): void
    {
        Schema::create('courts', function (Blueprint $table) {
            $table->id();

            // datos básicos
            $table->string('name');
            $table->text('description')->nullable();

            // relación con users (opcional)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('cascade');

            // datos adicionales
            $table->string('location')->nullable();
            $table->string('phone')->nullable();
            $table->json('amenities')->nullable();      // comodidades en JSON
            $table->time('opening_time')->default('08:00');
            $table->time('closing_time')->default('22:00');
            $table->enum('status', ['active', 'inactive', 'maintenance'])
                  ->default('active');
            $table->text('rules')->nullable();
            $table->integer('capacity')->default(2);    // n.º de jugadores

            $table->timestamps();                       // created_at y updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
}
