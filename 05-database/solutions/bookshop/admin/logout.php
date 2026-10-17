<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Auth;

// Logout changes state: POST only, with the CSRF token
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}
csrf_check();
Auth::logout();
redirect('login.php');
