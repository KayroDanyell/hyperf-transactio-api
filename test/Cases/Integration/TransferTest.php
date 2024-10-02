<?php

namespace HyperfTest\Cases\Integration;

use App\Exception\MerchantCannotTransferException;
use App\Model\User;
use App\Service\TransferService;
use HyperfTest\Abstract\TestHelper;
use Swoole\Http\Status;

class TransferTest extends TestHelper
{
    public TransferService $service;

    public User $payer;

    public User $payee;
    public float $transferAmount = 100.00;
    public function setUp(): void
    {
        $this->refreshContainer();
        $this->refreshDatabase();
        parent::setUp();
    }
    public function testTransferBetweenCommonUsers()
    {
        //arrange
        $this->mockTransferAuthorizationService();
        $payer = $this->createCommonUser();
        $payee = $this->createCommonUser();

        $body = $this->generateTransferBody($payer->id, $payee->id, $this->transferAmount);
        //act
        $response = $this->post('/api/transfer', $body);
        $responseContent = json_decode($response->getContent());

        //assert
        $this->assertEquals(Status::OK, $response->getStatusCode());
        $this->assertEquals($payer->id, $responseContent->data->payer);
        $this->assertEquals($payee->id, $responseContent->data->payee);

        $formattedTransferAmount = $this->formatValues($this->transferAmount);
        $this->assertEquals($formattedTransferAmount, $responseContent->data->value);

        $this->assertWalletsBalance($formattedTransferAmount, $payer, $payee);
    }

    public function testTransferBetweenMerchantAndUserException()
    {
        //arrange
        $this->mockTransferAuthorizationService();
        $payer = $this->createMerchantUser();
        $payee = $this->createCommonUser();

        $body = $this->generateTransferBody($payer->id, $payee->id, $this->transferAmount);

        //act
        $response = $this->post('/api/transfer', $body);
        //assert
        $this->assertException($response, MerchantCannotTransferException::CODE, MerchantCannotTransferException::DEFAULT_MESSAGE);
    }

    /** tests below has a conflict with external authorization service mock*/
    /*public function testExternalAuthorizationServiceNotAuthorized()
    {
        //arrange
        $this->mockTransferAuthorizationService(false);

        $payer = $this->createCommonUser();
        $payee = $this->createCommonUser();

        $body = $this->generateTransferBody($payer->id, $payee->id, $this->transferAmount);

        //act
        $response = $this->post('/api/transfer', $body);

        //assert
        $this->assertException($response, TransferNotAuthorizedException::CODE, TransferNotAuthorizedException::DEFAULT_MESSAGE);
    }*/
}