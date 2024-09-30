<?php

namespace App\Interface\Notification;

interface NotificationInterface
{
    public function send():bool;
    public function getSubscribers():array;
    public function setSubscribers(array $users):void;
    public function getType():string;


}