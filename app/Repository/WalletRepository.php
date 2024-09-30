<?php

namespace App\Repository;

use App\Exception\WalletInsufficientBalanceException;
use App\Model\Wallet;
use Hyperf\DbConnection\Db;

class WalletRepository extends AbstractRepository
{
    public function __construct(public Wallet $walletModel, Db $database)
    {
        parent::__construct($database);
    }

    public function getByOwnerId(string $ownerId) : Wallet
    {
        return $this->walletModel::where('owner_id', $ownerId)->first();
    }
    public function debit(Wallet $wallet, int $value) : Wallet
    {
        if (!$wallet->hasEnoughBalanceToWithdraw($value)) {
            throw new WalletInsufficientBalanceException();
        }
        $wallet->setBalance($wallet->getBalance() - $value);
        $wallet->save();
        return $wallet;
    }

    public function credit(Wallet $wallet, int $value) : Wallet
    {
        $wallet->setBalance($wallet->getBalance() + $value);
        $wallet->save();
        return $wallet;
    }
}