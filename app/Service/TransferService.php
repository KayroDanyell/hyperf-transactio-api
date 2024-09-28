<?php

namespace App\Service;

use App\DTO\TransferDTO;
use App\Exception\MerchantCannotTransferException;
use App\Exception\TransferNotAuthorizedException;
use App\External\Interface\TransferAuthorization\TransferAuthorizationServiceInterface;
use App\Repository\TransferRepository;
use App\Service\Wallet\WalletService;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class TransferService
{

    #[Inject]
    private TransferAuthorizationServiceInterface $transferAuthorizationService;

    #[Inject]
    private TransferRepository $transferRepository;

    #[Inject]
    private WalletService $walletService;

    public function __construct(
    ){}
    public function transfer(TransferDTO $transfer) : TransferDTO
    {
        try {
            DB::beginTransaction();

            $this->authorizeTransfer($transfer);

            $transfer = $this->transferRepository->save($transfer);
            $this->walletService->withdraw($transfer);
            $this->walletService->deposit($transfer);

            $this->transferRepository->confirmTransfer($transfer->getId());

            DB::commit();

            return $transfer;
        }catch(MerchantCannotTransferException | TransferNotAuthorizedException | WalletInsufficientBalanceException $e) {
            DB::rollBack();
            throw $e;
        }catch(\Throwable $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    private function authorizeTransfer(TransferDTO $transfer) : void
    {
        if ($transfer->getPayer()->isMerchant()) {
            throw new MerchantCannotTransferException();
        }

        $externalAuth = $this->transferAuthorizationService->authorizeTransfer($transfer);
        if (!$externalAuth) {
            throw new TransferNotAuthorizedException();
        }

        if ($transfer->getPayer() == $transfer->getPayee()) {} //TODO: passar para validacao da req

        $payer = $transfer->getPayer();

    }
}