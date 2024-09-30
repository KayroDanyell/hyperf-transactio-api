<?php

declare(strict_types=1);

namespace App\Request;

use App\Model\User;
use App\Repository\UserRepository;
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
            'payer' => ['required','exists:users,id'],
            'payee' => ['required','exists:users,id','different:payer'],
            'value' => ['required','numeric','min:1'],
        ];
    }

    public function getPayer(): User
    {
        return UserRepository::find($this->input('payer'));
    }

    public function setPayer(?string $payer): void
    {
        $this->payer = $payer;
    }

    public function getPayee(): User
    {
        return UserRepository::find($this->input('payee'));
    }

    public function setPayee(?string $payee): void
    {
        $this->payee = $payee;
    }

    public function getValue(): int
    {
        return (int) $this->input('value') * 100;
    }

    public function setValue(?float $value): void
    {
        $this->value = $value;
    }
}
