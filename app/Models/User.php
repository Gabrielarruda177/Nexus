<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'turma', 'turma_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function comunicados(): HasMany
    {
        return $this->hasMany(Comunicado::class, 'autor_id');
    }

    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class, 'autor_id');
    }

    public function turmaMatriculada(): BelongsTo
    {
        return $this->belongsTo(Turma::class, 'turma_id');
    }

    public function turmasLecionadas(): BelongsToMany
    {
        return $this->belongsToMany(Turma::class, 'turma_professor', 'user_id', 'turma_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'administrador';
    }

    public function isProfessor(): bool
    {
        return $this->role === 'professor';
    }

    public function isAluno(): bool
    {
        return $this->role === 'aluno';
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'administrador' => 'Administrador',
            'professor' => 'Professor',
            'aluno' => 'Aluno',
            default => ucfirst((string) $this->role),
        };
    }
}
