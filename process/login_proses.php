<?php
session_start();
include __DIR__ . '/../config/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../login.php');
    exit;
}
$username = is_string($_POST['username'] ?? '') ? trim($_POST['username'] ?? '') : '';
$password = is_string($_POST['password'] ?? '') ? ($_POST['password'] ?? '') : '';

$query = mysqli_prepare($konek, 'SELECT UsersID, Username, Password FROM users WHERE Username = ?');
mysqli_stmt_bind_param($query, 's', $username);
mysqli_stmt_execute($query);
$hasil = mysqli_stmt_get_result($query);

while ($akun = mysqli_fetch_assoc($hasil)) {
    $benar = $password === $akun['Password'];
    if ($benar) {
        session_regenerate_id(true);
        $_SESSION = [];
        $_SESSION['user_id'] = $akun['UsersID'];
        $_SESSION['username'] = $akun['Username'];
        $_SESSION['status'] = 'login';
        $_SESSION['login_at'] = time();
        header('Location: ../dashboard.php');
        exit;
    }
}
header('Location: ../login.php?error=1');
exit;
