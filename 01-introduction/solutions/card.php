<?php
$firstName = 'Alice';
$lastName = 'Martin';
$job = 'Web developer';
$birthYear = 1990;
$currentYear = (int) date('Y');
$age = $currentYear - $birthYear;
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Business card</title></head>
<body>
    <h1><?php echo $firstName . ' ' . $lastName; ?></h1>
    <p><?= $job ?></p>
    <p><?= $age ?> years old</p>
</body>
</html>
