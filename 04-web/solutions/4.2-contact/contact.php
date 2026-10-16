<?php
session_start();

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$subjects = [
    'info'    => 'Information request',
    'quote'   => 'Quote',
    'support' => 'Technical support',
];

$errors = [];
$data = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $field => $_) {
        $data[$field] = trim($_POST[$field] ?? '');
    }

    if ($data['name'] === '') {
        $errors['name'] = 'Name is required.';
    }
    if ($data['email'] === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email is not valid.';
    }
    if (!array_key_exists($data['subject'], $subjects)) {
        $errors['subject'] = 'Please choose a subject.';
    }
    if (mb_strlen($data['message']) < 10) {
        $errors['message'] = 'Message must be at least 10 characters.';
    }

    if (!$errors) {
        // Here we would send an email or save to a database.
        $_SESSION['flash'] = "Thank you {$data['name']}, your message has been sent.";
        header('Location: thanks.php');
        exit;
    }
}

// Small helper to display an error under a field
function error(array $errors, string $field): string
{
    return isset($errors[$field]) ? '<p style="color:red">' . e($errors[$field]) . '</p>' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Contact</title></head>
<body>
    <h1>Contact</h1>
    <form method="post">
        <p>
            <label>Name<br><input type="text" name="name" value="<?= e($data['name']) ?>"></label>
            <?= error($errors, 'name') ?>
        </p>
        <p>
            <label>Email<br><input type="email" name="email" value="<?= e($data['email']) ?>"></label>
            <?= error($errors, 'email') ?>
        </p>
        <p>
            <label>Subject<br>
                <select name="subject">
                    <option value="">-- choose --</option>
                    <?php foreach ($subjects as $value => $label): ?>
                        <option value="<?= e($value) ?>" <?= $data['subject'] === $value ? 'selected' : '' ?>>
                            <?= e($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <?= error($errors, 'subject') ?>
        </p>
        <p>
            <label>Message<br><textarea name="message" rows="5" cols="40"><?= e($data['message']) ?></textarea></label>
            <?= error($errors, 'message') ?>
        </p>
        <button type="submit">Send</button>
    </form>
</body>
</html>
