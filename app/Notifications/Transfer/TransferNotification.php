<?php

namespace App\Notifications\Transfer;

use App\Enum\Notification\NotificationTypesEnum;
use App\External\Request\NotificationRequest;
use App\Interface\Notification\NotificationInterface;
use App\Model\User;
use Hyperf\Contract\StdoutLoggerInterface;

class TransferNotification implements NotificationInterface
{

    private array $subscribers = [];

    private string $type;

    private NotificationRequest $request;

    public function __construct(
        private StdoutLoggerInterface $logger
    ){
        $this->request = make(NotificationRequest::class);
        $this->type = NotificationTypesEnum::EMAIL->value;
    }
    public function send():bool
    {
        $allSent = true;
        array_walk($this->subscribers, function ($subscriber) use (&$allSent) {
            try {
                $params = [
                    'message' => $subscriber['message'],
                    'user' => $subscriber['user']->getEmail()
                ];
                $this->request->makeRequest($params);
            }catch (\Throwable $e) {
                $this->logger->error('Error Sending Notification to: '.$subscriber['user']->getEmail().' '.$e->getMessage());
                $allSent = false;
            }
        });

        return $allSent;
    }

    public function getType():string
    {
        return $this->type;
    }
    public function getSubscribers():array
    {
        return $this->subscribers;
    }

    public function setSubscribers(array $users):void
    {
        $this->subscribers = $users;
    }

    public function addSubscriber(User $user,string $message):void
    {
        $this->subscribers[] = ['message' => $message, 'user' => $user] ;
    }
}