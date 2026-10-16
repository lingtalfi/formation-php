<?php
declare(strict_types=1);

namespace App\Notification;

class SmsNotifier implements Notifier
{
    private const MAX_LENGTH = 20;

    public function send(string $to, string $message): string
    {
        if (mb_strlen($message) > self::MAX_LENGTH) {
            $message = mb_substr($message, 0, self::MAX_LENGTH) . '...';
        }
        return "[SMS to $to] $message";
    }
}
