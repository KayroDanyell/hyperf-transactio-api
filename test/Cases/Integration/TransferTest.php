<?php

namespace HyperfTest\Cases\Integration;

use App\DTO\TransferDTO;
use App\Enum\UserTypesEnum;
use App\Exception\MerchantCannotTransferException;
use App\Exception\TransferNotAuthorizedException;
use App\External\Interface\TransferAuthorization\TransferAuthorizationServiceInterface;
use App\External\Request\ExternalTransferAuthorizationRequest;
use App\External\Service\TransferAuthorization\ExternalTransferAuthorizationService;
use App\Model\Transfer;
use App\Model\User;
use App\Model\Wallet;

use App\Service\TransferService;
use Hyperf\Context\ApplicationContext;
use HyperfTest\AbstractTest;
use Mockery;
use PHPUnit\Framework\TestCase;
use Swoole\Http\Status;

class TransferTest extends AbstractTest
{
    public TransferService $service;

    public TransferAuthorizationServiceInterface $authorizationService;

    public User $payer;

    public User $payee;
    public function setUp(): void
    {
//        $this->mockTransferAuthorizationService();
        $this->service = make(TransferService::class);
        $this->authorizationService = make(TransferAuthorizationServiceInterface::class);
        parent::setUp();
    }
    public function testTransferBetweenCommonUsers()
    {
        //arrange
        $this->mockTransferAuthorizationService();
        $this->payer = $this->createCommonUser();
        $this->payee = $this->createCommonUser();
        $transferAmount = 100.00;

        $body = $this->generateTransferBody($this->payer->id, $this->payee->id, $transferAmount);
        //act
        $response = $this->post('/api/transfer', $body);
        $responseContent = json_decode($response->getContent());

        //assert
        $this->assertEquals(Status::OK, $response->getStatusCode());

        $this->assertEquals($this->payer->id, $responseContent->data->payer);

        $this->assertEquals($this->payee->id, $responseContent->data->payee);

        $formattedTransferAmount = $this->formatValues($transferAmount);
        $this->assertEquals($formattedTransferAmount, $responseContent->data->value);

        $this->assertEquals(
            self::DEFAULT_BALANCE  - $formattedTransferAmount, $this->payer->wallet()->first()->balance);
        $this->assertEquals(
            self::DEFAULT_BALANCE  + $formattedTransferAmount, $this->payee->wallet()->first()->balance);
        
    }

    public function testTransferBetweenMerchantAndUserException()
    {
        //arrange
        $this->mockTransferAuthorizationService();
        $this->payer = $this->createMerchantUser();
        $this->payee = $this->createCommonUser();
        $transferAmount = 100.00;

        $body = $this->generateTransferBody($this->payer->id, $this->payee->id, $transferAmount);

        $this->expectException(MerchantCannotTransferException::class);
        //act
        $response = $this->post('/api/transfer', $body);
        //assert
        $this->assertException($response, Status::INTERNAL_SERVER_ERROR, MerchantCannotTransferException::DEFAULT_MESSAGE);
    }

    public function testExternalAuthorizationServiceNotAuthorized()
    {
        //arrange
        $this->mockTransferAuthorizationService(false);

        $this->payer = $this->createCommonUser();
        $this->payee = $this->createCommonUser();
        $transferAmount = 100.00;
        $body = $this->generateTransferBody($this->payer->id, $this->payee->id, $transferAmount);
        $this->expectException(TransferNotAuthorizedException::class);

        //act
        $response = $this->post('/api/transfer', $body);

        //assert
        $this->assertException($response, Status::INTERNAL_SERVER_ERROR, TransferNotAuthorizedException::DEFAULT_MESSAGE);
    }

}