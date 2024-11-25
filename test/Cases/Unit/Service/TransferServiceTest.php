<?php

namespace HyperfTest\Cases\Unit\Service;

use App\DTO\TransferDTO;
use App\Exception\MerchantCannotTransferException;
use App\Exception\WalletInsufficientBalanceException;
use App\Model\User;
use App\Service\TransferService;
use HyperfTest\Abstract\TestHelper;

class TransferServiceTest extends TestHelper
{
    protected TransferService $service;
    public User $payer;

    public User $payee;
    public float $transferAmount = 100.00;
    public function setUp():void
    {
        $this->refreshContainer();
        $this->refreshDatabase();
        parent::setUp();
        $this->service = make(TransferService::class, [
            'transferAuthorizationService' => $this->mockTransactionAuthorizedServiceForUnitTest(),
        ]);
    }

    /*public function testServiceTransferBetweenCommonUsers()
    {
        //arrange
        $payer = $this->createCommonUser();
        $payee = $this->createCommonUser();

        $dto = new TransferDTO($payer, $payee, $this->transferAmount * 100);

        $this->service->transfer($dto);

        $formattedTransferAmount = $this->formatValues($this->transferAmount);

        $this->assertWalletsBalance($formattedTransferAmount, $payer, $payee);
    }
    public function testServiceBetweenMerchantAndUserException()
    {
        //arrange
        $payer = $this->createMerchantUser();
        $payee = $this->createCommonUser();

        $dto = new TransferDTO($payer, $payee, $this->transferAmount * 100);

        $this->expectException(MerchantCannotTransferException::class);
        $this->service->transfer($dto);

    }*/
    /** tests below has a conflict with external authorization service mock */
    /*public function testServiceInsufficientWalletBalanceException()
    {
        //arrange
        $this->refreshContainer();
        $payer = $this->createCommonUser(0);
        $payee = $this->createCommonUser();

        $dto = new TransferDTO($payer, $payee, $this->transferAmount * 100);

        $this->expectException(WalletInsufficientBalanceException::class);
        $this->service->transfer($dto);

        $this->assertEquals(0, $payer->wallet()->first()->balance);
        $this->assertEquals(self::DEFAULT_BALANCE, $payee->wallet()->first()->balance);
    }

    public function testServiceTransferBetweenCommonAndMerchantUsers()
    {
        //arrange
        $payer = $this->createCommonUser();
        $payee = $this->createMerchantUser();
        $transferAmount = 100.00;

        $dto = new TransferDTO($payer, $payee, $transferAmount);

        $this->service->transfer($dto);

        $formattedTransferAmount = $this->formatValues($transferAmount);
        $this->>assertWalletsBalance($formattedTransferAmount);
    }*/

}