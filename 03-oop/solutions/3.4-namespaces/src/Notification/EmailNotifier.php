<?php
declare(strict_types=1);

namespace App\Notification;

class EmailNotifier implements Notifier
{
    public function send(string $to, string $message): string
    {
        return "[EMAIL to $to] $message";
    }
}
