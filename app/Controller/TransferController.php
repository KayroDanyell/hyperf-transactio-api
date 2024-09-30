<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\TransferDTO;
use App\Exception\MerchantCannotTransferException;
use App\Request\TransferRequest;
use App\Resource\TransferResource;
use App\Service\TransferService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Swoole\Http\Status;


class TransferController
{
//    #[Inject]
    protected TransferService $transferService;

    public function __construct() {
        $this->transferService = make(TransferService::class);
    }
    public function transfer(TransferRequest $request) : ResponseInterface
    {
        $transfer = new TransferDTO(
            payer: $request->getPayer(),
            payee: $request->getPayee(),
            value: $request->getValue()
        );

        $confirmedTransfer = $this->transferService->transfer($transfer);

        return TransferResource::make($confirmedTransfer)->toResponse()->withStatus(Status::OK);
    }

    public function testeThrow()
    {
        throw new MerchantCannotTransferException();
    }
}
