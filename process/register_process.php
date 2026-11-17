<?php
session_start();
include __DIR__ . '/../config/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../register.php');
    exit;
}
$username = is_string($_POST['username'] ?? '') ? trim($_POST['username'] ?? '') : '';
$name = is_string($_POST['name'] ?? '') ? trim($_POST['name'] ?? '') : '';
$password = is_string($_POST['password'] ?? '') ? ($_POST['password'] ?? '') : '';
$pesan = '';
if ($username == '' || $name == '' || trim($password) == '') {
    $pesan = 'Lengkapi semua kolom.';
} elseif (mb_strlen($username) > 16 || mb_strlen($name) > 16 || strlen($password) > 72) {
    $pesan = 'Username dan nickname maksimal 16 karakter. Password maksimal 72 byte.';
} else {
    $query = mysqli_prepare($konek, 'SELECT UsersID FROM users WHERE Username = ?');
    mysqli_stmt_bind_param($query, 's', $username);
    mysqli_stmt_execute($query);
    if (mysqli_num_rows(mysqli_stmt_get_result($query)) > 0) $pesan = 'Username sudah dipakai.';

    $query = mysqli_prepare($konek, 'SELECT UsersID FROM users WHERE Name = ?');
    mysqli_stmt_bind_param($query, 's', $name);
    mysqli_stmt_execute($query);
    if ($pesan == '' && mysqli_num_rows(mysqli_stmt_get_result($query)) > 0) $pesan = 'Nickname sudah dipakai.';
}
if ($pesan == '') {
    $query = mysqli_prepare($konek, 'INSERT INTO users (Username, Password, Name) VALUES (?, ?, ?)');
    mysqli_stmt_bind_param($query, 'sss', $username, $password, $name);
    if (mysqli_stmt_execute($query)) {
        header('Location: ../login.php');
        exit;
    }
    $pesan = 'Akun belum bisa dibuat. Silakan coba lagi.';
}
$_SESSION['error_register'] = $pesan;
header('Location: ../register.php');
exit;
