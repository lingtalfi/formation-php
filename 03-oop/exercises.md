# 03 — Exercises

Command-line scripts: `php file.php`.

## 3.1 — Bank account

Create a `BankAccount` class:

- private property `$balance`, a public readonly property `$owner` (set in the constructor)
- `deposit(float $amount)`: refuses amounts <= 0 (throw `InvalidArgumentException`)
- `withdraw(float $amount)`: refuses if the balance would go below zero (throw an exception)
- `getBalance(): float`

Test it: create an account, make a few operations, then try an invalid withdrawal inside a `try/catch` and display the error message.

## 3.2 — Shapes (inheritance and polymorphism)

1. Create an abstract class `Shape` with an abstract method `area(): float` and a concrete method `describe(): string` returning e.g. `Circle with area 78.54`.
2. Create `Circle` (radius), `Rectangle` (width, height) and `Square` (side). `Square` should **extend `Rectangle`**.
3. Put one of each in an array, loop over it and display each `describe()`.
4. Sort the array by area with `usort` and display again.

Hint: `M_PI` is π. `static::class` gives the real class name of the object.

## 3.3 — Notifications (interfaces)

1. Create an interface `Notifier` with a method `send(string $to, string $message): string`.
2. Implement it in `EmailNotifier` and `SmsNotifier` (they just return a text like `[EMAIL to bob@mail.com] Hello`).
3. `SmsNotifier` should cut messages longer than 20 characters and add `...`.
4. Write a function `notifyAll(array $notifiers, string $to, string $message)` that sends with each notifier.

## 3.4 — Namespaces and autoload

Reorganize exercise 3.3 in a folder:

```
3.4-namespaces/
├── index.php
└── src/
    └── Notification/
        ├── Notifier.php
        ├── EmailNotifier.php
        └── SmsNotifier.php
```

- Each class in namespace `App\Notification`.
- `index.php` registers an autoloader with `spl_autoload_register`, imports the classes with `use`, and runs the same test as 3.3.
- No `require` of the class files themselves.
