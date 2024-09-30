<?php

namespace HyperfTest\Cases\Unit\Service;

use App\DTO\TransferDTO;
use App\Exception\MerchantCannotTransferException;
use App\Exception\WalletInsufficientBalanceException;
use App\Model\User;
use App\Service\TransferService;
use HyperfTest\AbstractTest;
use Swoole\Http\Status;

class TransferService1Test extends AbstractTest
{
    protected TransferService $service;
    public User $payer;

    public User $payee;
    public function setUp():void
    {
        $this->service = make(TransferService::class);
        parent::setUp();
    }

    public function testServiceTransferBetweenCommonUsers()
    {
        //arrange
        $this->payer = $this->createCommonUser();
        $this->payee = $this->createCommonUser();
        $transferAmount = 100.00;

        $dto = new TransferDTO($this->payer, $this->payee, $transferAmount);

        $this->service->transfer($dto);

        $formattedTransferAmount = $this->formatValues($transferAmount);
        var_dump($formattedTransferAmount);
        $this->assertEquals(self::DEFAULT_BALANCE - $formattedTransferAmount, $this->payer->wallet()->first()->balance);
        $this->assertEquals(self::DEFAULT_BALANCE + $formattedTransferAmount, $this->payee->wallet()->first()->balance);
    }

    public function testServiceTransferBetweenCommonAndMerchantUsers()
    {
        //arrange
        $this->payer = $this->createCommonUser();
        $this->payee = $this->createMerchantUser();
        $transferAmount = 100.00;

        $dto = new TransferDTO($this->payer, $this->payee, $transferAmount);

        $this->service->transfer($dto);

        $formattedTransferAmount = $this->formatValues($transferAmount);
        $this->assertEquals(self::DEFAULT_BALANCE - $formattedTransferAmount, $this->payer->wallet()->first()->balance);
        $this->assertEquals(self::DEFAULT_BALANCE + $formattedTransferAmount, $this->payee->wallet()->first()->balance);
    }

    public function testServiceBetweenMerchantAndUserException()
    {
        //arrange
        $this->payer = $this->createMerchantUser();
        $this->payee = $this->createCommonUser();
        $transferAmount = 100.00;

        $dto = new TransferDTO($this->payer, $this->payee, $transferAmount);

        $this->expectException(MerchantCannotTransferException::class);
        $this->service->transfer($dto);

    }

    public function testServiceInsufficientWalletBalanceException()
    {
        //arrange
        $this->payer = $this->createCommonUser(0);
        $this->payee = $this->createCommonUser();
        $transferAmount = 100.00;

        $dto = new TransferDTO($this->payer, $this->payee, $transferAmount);

        $this->expectException(WalletInsufficientBalanceException::class);
        $this->service->transfer($dto);
    }
}