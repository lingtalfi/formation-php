<?php
const MAX_SIZE = 2 * 1024 * 1024;   // 2 MB
const UPLOAD_DIR = __DIR__ . '/uploads';
const ALLOWED = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
];

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['avatar'] ?? null;

    if ($file === null || $file['error'] === UPLOAD_ERR_NO_FILE) {
        $message = 'Please choose a file.';
    } elseif ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
        $message = 'File too large (server limit).';
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $message = 'Upload error (code ' . $file['error'] . ').';
    } elseif ($file['size'] > MAX_SIZE) {
        $message = 'File too large: 2 MB maximum.';
    } else {
        // Check the REAL content type, not the extension
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);

        if (!isset(ALLOWED[$mime])) {
            $message = "Type not allowed ($mime). JPEG, PNG or WebP only.";
        } else {
            $newName = bin2hex(random_bytes(8)) . '.' . ALLOWED[$mime];
            if (move_uploaded_file($file['tmp_name'], UPLOAD_DIR . '/' . $newName)) {
                $message = 'Image uploaded.';
            } else {
                $message = 'Could not save the file.';
            }
        }
    }
}

// Keep only files with an allowed extension
$images = array_filter(
    glob(UPLOAD_DIR . '/*') ?: [],
    fn($path) => in_array(pathinfo($path, PATHINFO_EXTENSION), ALLOWED, true)
);
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Upload</title></head>
<body>
    <h1>Avatar upload</h1>
    <?php if ($message): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp">
        <button type="submit">Upload</button>
    </form>

    <h2>Uploaded images</h2>
    <?php foreach ($images as $path): ?>
        <img src="uploads/<?= htmlspecialchars(basename($path)) ?>" width="100" alt="">
    <?php endforeach; ?>
</body>
</html>
