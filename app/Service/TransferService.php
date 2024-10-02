<?php

namespace App\Service;

use App\DTO\TransferDTO;
use App\Enum\Notification\NotificationMessagesEnum;
use App\Exception\MerchantCannotTransferException;
use App\Exception\TransferNotAuthorizedException;
use App\Exception\WalletInsufficientBalanceException;
use App\External\Service\TransferAuthorization\ExternalTransferAuthorizationService;
use App\Interface\Notification\NotificationInterface;
use App\Repository\TransferRepository;
use App\Service\Wallet\WalletService;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class TransferService
{
    #[Inject]
    private TransferRepository $transferRepository;
    #[Inject]
    private WalletService $walletService;

    private NotificationInterface $notification;
    private ExternalTransferAuthorizationService $transferAuthorizationService;

    public function __construct(

    ){
        $this->transferAuthorizationService = make(ExternalTransferAuthorizationService::class);
        $this->notification = make(NotificationInterface::class);
    }
    public function transfer(TransferDTO $transfer) : TransferDTO
    {
        try {
            DB::beginTransaction();

            $this->authorizeTransfer($transfer);

            $transfer = $this->transferRepository->save($transfer);
            $this->walletService->withdraw($transfer->getPayer(), $transfer->getValue());
            $this->walletService->deposit($transfer->getPayee(), $transfer->getValue());

            $this->transferRepository->confirmTransfer($transfer->getId());

            $this->notification->setSubscribers([
                ['user'=>$transfer->getPayer(),'message'=>NotificationMessagesEnum::SEND_TRANSFER->value],
                ['user'=>$transfer->getPayee(),'message'=>NotificationMessagesEnum::RECEIVE_TRANSFER->value]
            ]);
            $this->notification->send();

            DB::commit();

            return $transfer;
        }catch(MerchantCannotTransferException | TransferNotAuthorizedException | WalletInsufficientBalanceException $e) {
            DB::rollBack();
            throw $e;
        }catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function authorizeTransfer(TransferDTO $transfer) : void
    {
        if ($transfer->getPayer()->isMerchant()) {
            throw new MerchantCannotTransferException();
        }

        $externalAuth = $this->transferAuthorizationService->externalAuthorizeTransfer($transfer);
        if (!$externalAuth) {
            throw new TransferNotAuthorizedException();
        }
    }
}