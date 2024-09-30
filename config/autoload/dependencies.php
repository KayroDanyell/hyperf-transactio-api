<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use App\External\Interface\TransferAuthorization\TransferAuthorizationServiceInterface;
use App\External\Service\TransferAuthorization\ExternalTransferAuthorizationService;
use App\Interface\Notification\NotificationInterface;
use App\Notifications\Transfer\TransferNotification;

return [
    TransferAuthorizationServiceInterface::class => ExternalTransferAuthorizationService::class,
    NotificationInterface::class => TransferNotification::class
];
