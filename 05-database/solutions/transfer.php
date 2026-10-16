<?php
declare(strict_types=1);

$pdo = new PDO('sqlite:' . __DIR__ . '/bank.db', null, null, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

// 1. Fresh state at each run
$pdo->exec('DROP TABLE IF EXISTS accounts');
$pdo->exec('CREATE TABLE accounts (id INTEGER PRIMARY KEY, owner TEXT NOT NULL, balance REAL NOT NULL)');
$pdo->exec("INSERT INTO accounts (id, owner, balance) VALUES (1, 'Alice', 500), (2, 'Bob', 100)");

// 2. Transfer
function transfer(PDO $pdo, int $from, int $to, float $amount): void
{
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('SELECT balance FROM accounts WHERE id = ?');
        $stmt->execute([$from]);
        $balance = $stmt->fetchColumn();

        if ($balance === false) {
            throw new RuntimeException("Account $from not found.");
        }
        if ($balance < $amount) {
            throw new RuntimeException("Insufficient funds on account $from ($balance).");
        }

        $pdo->prepare('UPDATE accounts SET balance = balance - ? WHERE id = ?')->execute([$amount, $from]);
        $pdo->prepare('UPDATE accounts SET balance = balance + ? WHERE id = ?')->execute([$amount, $to]);

        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;   // let the caller know it failed
    }
}

function showBalances(PDO $pdo): void
{
    foreach ($pdo->query('SELECT owner, balance FROM accounts') as $row) {
        echo "  {$row['owner']}: {$row['balance']}" . PHP_EOL;
    }
}

// 3. Tests
foreach ([[1, 2, 200], [2, 1, 1000]] as [$from, $to, $amount]) {
    echo "Transfer $amount from $from to $to..." . PHP_EOL;
    try {
        transfer($pdo, $from, $to, $amount);
        echo '  OK' . PHP_EOL;
    } catch (RuntimeException $e) {
        echo '  FAILED: ' . $e->getMessage() . PHP_EOL;
    }
    showBalances($pdo);
}
