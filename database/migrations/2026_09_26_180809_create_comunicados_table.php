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
        Schema::create('comunicados', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->enum('categoria', ['Acadêmico', 'Eventos', 'Avisos', 'Turmas', 'Urgente'])->default('Avisos');
            $table->string('turma', 100)->nullable();
            $table->foreignId('autor_id')->constrained('users')->cascadeOnDelete();
            $table->enum('importancia', ['normal', 'importante', 'urgente'])->default('normal');
            $table->date('data_evento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunicados');
    }
};
