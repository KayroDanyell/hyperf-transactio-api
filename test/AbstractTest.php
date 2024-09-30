<?php

namespace HyperfTest;

use App\Enum\UserTypesEnum;
use App\External\Interface\TransferAuthorization\TransferAuthorizationServiceInterface;
use App\External\Service\TransferAuthorization\ExternalTransferAuthorizationService;
use App\Model\User;
use App\Model\Wallet;
use App\Repositories\UserRepository;
use Hyperf\Context\ApplicationContext;
use Hyperf\Testing\Concerns\MakesHttpRequests;
use Hyperf\Testing\Http\TestResponse;
use Mockery;
use PHPUnit\Framework\TestCase;

class AbstractTest extends TestCase
{

    use MakesHttpRequests;

    const DEFAULT_BALANCE = 100000;
    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    public function createCommonUser(int $walletBalance = self::DEFAULT_BALANCE) : User
    {
        $user = factory(User::class)->create([
                'type' => UserTypesEnum::COMMON->value
        ]);
        $user->save();
        $wallet = factory(Wallet::class)->create([
            'owner_id' => $user->id,
            'balance' => $walletBalance
        ]);
        return $user;
    }

    public function createMerchantUser(int $walletBalance = self::DEFAULT_BALANCE) : User
    {
        $user = factory(User::class)->create([
            'type' => UserTypesEnum::MERCHANT->value
        ]);
        $wallet = factory(Wallet::class)->create([
            'owner_id' => $user->id,
            'balance' => $walletBalance
        ]);
        return $user;
    }

    protected function mockTransferAuthorizationService(bool $return = true): void
    {
        $mock = Mockery::mock(ExternalTransferAuthorizationService::class)
            ->shouldReceive('externalAuthorizeTransfer')
            ->andReturn($return);


        $container = ApplicationContext::getContainer();
        $container->define(
            ExternalTransferAuthorizationService::class,
            fn () => $mock->getMock()->makePartial(),
        );
    }



    public function formatValues(float|int $value):int
    {
        return (int) ($value * 100);
    }

    public function generateTransferBody($payerId, $payeeId, $value = 100.00): array
    {
        return [
            'value' => $value,
            'payer' => $payerId,
            'payee' => $payeeId,
        ];
    }

    public function assertException(TestResponse $response,int $expectedCode, string $expectedMessage)
    {
        $responseContent = $response->getBody()->getContents();
        $this->assertEquals($expectedCode, $response->getStatusCode());
        $this->assertEquals($expectedMessage, $responseContent);
    }

    public function tearDown():void {
        Mockery::close();
    }
}