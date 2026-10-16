<?php
declare(strict_types=1);

interface Notifier
{
    public function send(string $to, string $message): string;
}

class EmailNotifier implements Notifier
{
    public function send(string $to, string $message): string
    {
        return "[EMAIL to $to] $message";
    }
}

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

function notifyAll(array $notifiers, string $to, string $message): void
{
    foreach ($notifiers as $notifier) {
        echo $notifier->send($to, $message) . PHP_EOL;
    }
}

notifyAll(
    [new EmailNotifier(), new SmsNotifier()],
    'bob',
    'Your order has been shipped and will arrive tomorrow.'
);
