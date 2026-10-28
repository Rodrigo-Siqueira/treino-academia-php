<?php

namespace App\Http\Resources;

use App\Models\ExercicioModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ExercicioModel
 */
class ExercicioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, int|string|bool>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'ativo' => $this->ativo,
        ];
    }
}
