<?php

namespace App\Repository;

use App\Model\Wallet;

class WalletRepository extends AbstractRepository
{
    public function debit(Wallet $wallet, int $value) : Wallet
    {
        if (!$wallet->hasEnoughBalanceToWithdraw($value)) {
            throw new WalletInsufficientBalanceException();
        }
        $wallet->setBalance($wallet->getBalance() - $value);
    }

    public function credit(Wallet $wallet, int $value) : Wallet
    {

    }
}