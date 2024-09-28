<?php

namespace App\External\Interface\TransferAuthorization;

use App\DTO\TransferDTO;

interface TransferAuthorizationServiceInterface
{
    public function authorizeTransfer(TransferDTO $transfer) : bool;
}