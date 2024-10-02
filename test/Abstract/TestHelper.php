<?php

namespace HyperfTest\Abstract;

use App\Enum\UserTypesEnum;
use App\External\Service\TransferAuthorization\ExternalTransferAuthorizationService;
use App\Model\User;
use App\Model\Wallet;
use Hyperf\Context\ApplicationContext;
use Hyperf\Testing\Http\TestResponse;
use Hyperf\Testing\TestCase;
use Mockery;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

class TestHelper extends TestCase
{

    const DEFAULT_BALANCE = 100000;
    public function __construct(string $name)
    {
        parent::__construct($name);
    }


    public function setUp(): void
    {
        parent::setUp();
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

    protected function mockTransactionAuthorizedServiceForUnitTest(bool $return = true): ExternalTransferAuthorizationService
    {
        return Mockery::mock(ExternalTransferAuthorizationService::class)
            ->shouldReceive('externalAuthorizeTransfer')
            ->andReturn($return)
            ->getMock()
            ->makePartial();
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

    public function assertWalletsBalance(int $formattedTransferAmount, User $payer, User $payee)
    {
        $walletSubtractedBalance = self::DEFAULT_BALANCE - $formattedTransferAmount;
        $this->assertEquals($walletSubtractedBalance, $payer->wallet()->first()->balance);

        $walletAddedBalance = self::DEFAULT_BALANCE + $formattedTransferAmount;
        $this->assertEquals($walletAddedBalance, $payee->wallet()->first()->balance);
    }

    public function refreshDatabase()
    {
        $container = ApplicationContext::getContainer();
        $container->get('Hyperf\Database\Commands\Migrations\FreshCommand')->run(
            new StringInput(''),
            new NullOutput()
        );
    }
}