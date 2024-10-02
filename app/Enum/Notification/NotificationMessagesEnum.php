<?php

namespace App\Enum\Notification;

enum NotificationMessagesEnum : string
{
    case RECEIVE_TRANSFER = 'You have received a transfer!';
    case SEND_TRANSFER = 'Your transfer has been sent!';

}
