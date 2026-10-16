<?php
declare(strict_types=1);

class BankAccount
{
    private float $balance = 0;

    public function __construct(public readonly string $owner) {}

    public function deposit(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Deposit must be positive.');
        }
        $this->balance += $amount;
    }

    public function withdraw(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Withdrawal must be positive.');
        }
        if ($amount > $this->balance) {
            throw new RuntimeException("Insufficient funds: balance is {$this->balance}.");
        }
        $this->balance -= $amount;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }
}

$account = new BankAccount('Alice');
$account->deposit(100);
$account->withdraw(30);
echo "{$account->owner}: {$account->getBalance()} €" . PHP_EOL;   // Alice: 70 €

try {
    $account->withdraw(500);
} catch (RuntimeException $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}

try {
    $account->owner = 'Mallory';   // readonly: Error (not Exception)
} catch (Error $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
