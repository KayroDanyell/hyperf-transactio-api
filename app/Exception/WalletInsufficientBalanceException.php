<?php

namespace App\Exception;

use Swoole\Http\Status;
use Throwable;

class WalletInsufficientBalanceException extends \Exception
{
    const CODE = Status::CONFLICT;
    const DEFAULT_MESSAGE = "Your wallet does not have enough balance!";

    public function __construct($message = self::DEFAULT_MESSAGE, $code = self::CODE, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getDefaultMessage(): string
    {
        return self::DEFAULT_MESSAGE;
    }
}