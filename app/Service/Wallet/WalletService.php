<?php

namespace App\Service\Wallet;

use App\Model\User;
use App\Repository\WalletRepository;
use Hyperf\Di\Annotation\Inject;

class WalletService
{
    #[Inject]
    private WalletRepository $walletRepository;

    public function __construct()
    {}

    public function withdraw(User $user, int $value)
    {
        $wallet = $this->walletRepository->getByOwnerId($user->getId());

        return $this->walletRepository->debit($wallet, $value);

    }

    public function deposit()
    {

    }


}