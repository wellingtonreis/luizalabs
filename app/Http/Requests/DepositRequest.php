<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response as ResponseStatusCode;

class DepositRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numberAccountOrigin' => 'required|integer',
            'value' => 'required|numeric',
            'type' => 'required|string|in:deposit',
            'description' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'numberAccountOrigin.required' => 'A conta de origem é obrigatória',
            'numberAccountOrigin.integer' => 'A conta de origem deve ser um número inteiro',
            'value.required' => 'O valor é obrigatório',
            'value.numeric' => 'O valor deve ser um número',
            'type.required' => 'O tipo é obrigatório',
            'type.string' => 'O tipo deve ser uma string',
            'type.in' => 'O tipo deve ser deposit',
            'description.string' => 'A descrição deve ser uma string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error' => $validator->errors()->first(),
        ], ResponseStatusCode::HTTP_UNPROCESSABLE_ENTITY));
    }
}
