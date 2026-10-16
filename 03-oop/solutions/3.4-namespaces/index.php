<?php
declare(strict_types=1);

// Autoloader: App\Notification\SmsNotifier -> src/Notification/SmsNotifier.php
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;   // not one of ours: let other autoloaders try
    }
    $relative = substr($class, strlen($prefix));
    $file = __DIR__ . '/src/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

use App\Notification\EmailNotifier;
use App\Notification\Notifier;
use App\Notification\SmsNotifier;

/** @param Notifier[] $notifiers */
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
