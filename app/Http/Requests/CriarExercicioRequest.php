<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CriarExercicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'nome' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do exercício é obrigatório.',
            'nome.string' => 'O nome do exercício deve ser um texto.',
            'nome.max' => 'O nome do exercício deve possuir no máximo 255 caracteres.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $nome = $this->input('nome');

        if (is_string($nome)) {
            $this->merge([
                'nome' => trim($nome),
            ]);
        }
    }
}
