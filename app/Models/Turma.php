<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turma extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'turmas';

    protected $fillable = [
        'nome',
        'codigo',
        'curso',
        'semestre',
        'periodo',
        'descricao',
    ];

    public function alunos(): HasMany
    {
        return $this->hasMany(User::class, 'turma_id')->where('role', 'aluno');
    }

    public function professores(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'turma_professor', 'turma_id', 'user_id');
    }

    public function comunicados(): HasMany
    {
        return $this->hasMany(Comunicado::class, 'turma_id');
    }

    public function getLabelCompletoAttribute(): string
    {
        return "{$this->codigo} - {$this->nome} ({$this->periodo})";
    }
}
