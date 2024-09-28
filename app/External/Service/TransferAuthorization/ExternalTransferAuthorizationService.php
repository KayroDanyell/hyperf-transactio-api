<?php

namespace App\External\Service\TransferAuthorization;

use App\DTO\TransferDTO;
use App\External\Interface\TransferAuthorization\TransferAuthorizationServiceInterface;
use App\External\Request\ExternalTransferAuthorizationRequest;
use Hyperf\Di\Annotation\Inject;

class ExternalTransferAuthorizationService implements TransferAuthorizationServiceInterface
{

    #[Inject]
    private ExternalTransferAuthorizationRequest $transferAuthorizationRequest;

    public function __construct()
    {}

    public function authorizeTransfer(TransferDTO $transfer): bool
    {
        return $this->transferAuthorizationRequest->authorize();
    }
}