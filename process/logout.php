<?php
include __DIR__ . '/../includes/app.php';
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    exit('Gunakan form yang tersedia.');
}
if (!$pengguna) {
    header('Location: ../login.php?error=expired');
    exit;
}
if (!is_string($_POST['csrf'] ?? null) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
    http_response_code(403);
    exit('Form kedaluwarsa. Muat ulang halaman dan coba lagi.');
}

session_unset();
session_destroy();
header('Location: ../login.php');
exit;
