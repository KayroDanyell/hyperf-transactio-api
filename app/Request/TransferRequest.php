<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: melhorar validacao, implementar validators e rules
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'payer' => ['required','numeric','exists:users,id'],
            'payee' => ['required','numeric','exists:users,id'],
            'value' => ['required','numeric','min:1'],
        ];
    }
}
