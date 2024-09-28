<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\TransferDTO;
use App\Request\TransferRequest;
use App\Resource\TransferResource;
use App\Service\TransferService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;


class TransferController
{
    #[Inject]
    protected TransferService $transferService;
    public function transfer(TransferRequest $request) : ResponseInterface
    {
        $transfer = TransferDTO::fromArray($request->validated());

        $confirmedTransfer = $this->transferService->transfer($transfer);

        return TransferResource::make($confirmedTransfer)->toResponse();
    }
}
