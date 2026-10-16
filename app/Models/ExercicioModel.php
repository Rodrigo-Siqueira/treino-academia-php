<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nome
 * @property bool $ativo
 */
final class ExercicioModel extends Model
{
    protected $table = 'exercicios';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nome',
        'ativo',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }
}
