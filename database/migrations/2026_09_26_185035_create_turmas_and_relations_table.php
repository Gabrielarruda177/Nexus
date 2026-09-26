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
        Schema::create('turmas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('codigo')->unique();
            $table->string('curso');
            $table->string('semestre', 50);
            $table->string('periodo', 50);
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        Schema::create('turma_professor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turma_id')->constrained('turmas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['turma_id', 'user_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('turma_id')->nullable()->after('turma')->constrained('turmas')->nullOnDelete();
        });

        Schema::table('comunicados', function (Blueprint $table) {
            $table->foreignId('turma_id')->nullable()->after('turma')->constrained('turmas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comunicados', function (Blueprint $table) {
            $table->dropForeign(['turma_id']);
            $table->dropColumn('turma_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['turma_id']);
            $table->dropColumn('turma_id');
        });

        Schema::dropIfExists('turma_professor');
        Schema::dropIfExists('turmas');
    }
};
