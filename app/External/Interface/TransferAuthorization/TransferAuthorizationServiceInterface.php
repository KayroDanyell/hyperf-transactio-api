<?php

namespace App\External\Interface\TransferAuthorization;

use App\DTO\TransferDTO;

interface TransferAuthorizationServiceInterface
{
    public function __construct();
    public function externalAuthorizeTransfer(TransferDTO $transfer);
}