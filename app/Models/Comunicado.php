<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comunicado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'comunicados';

    protected $fillable = [
        'titulo',
        'descricao',
        'categoria',
        'turma',
        'turma_id',
        'autor_id',
        'importancia',
        'data_evento',
    ];

    protected function casts(): array
    {
        return [
            'data_evento' => 'date',
        ];
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function turmaRelacionada(): BelongsTo
    {
        return $this->belongsTo(Turma::class, 'turma_id');
    }

    public function isUrgente(): bool
    {
        return $this->importancia === 'urgente' || $this->categoria === 'Urgente';
    }

    public function isImportante(): bool
    {
        return $this->importancia === 'importante';
    }

    public function getCategoriaColorAttribute(): string
    {
        return match ($this->categoria) {
            'Acadêmico' => '#0097b2',
            'Eventos' => '#1C8C82',
            'Avisos' => '#D98C2B',
            'Turmas' => '#19707F',
            'Urgente' => '#D64545',
            default => '#0097b2',
        };
    }
}
