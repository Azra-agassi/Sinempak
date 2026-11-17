<?php

session_start();

include __DIR__ . '/../config/koneksi.php';

include __DIR__ . '/film.php';

if (isset($_SESSION['login_at']) && time() >= $_SESSION['login_at'] + 300) {

    session_unset();

    session_regenerate_id(true);

}

if (!isset($_SESSION['csrf'])) {

    $_SESSION['csrf'] = bin2hex(random_bytes(32));

}

$pengguna = null;

if (isset($_SESSION['user_id'])) {

    $idPengguna = (int) $_SESSION['user_id'];

    $query = mysqli_prepare($konek, 'SELECT UsersID, Name, Username FROM users WHERE UsersID = ?');

    mysqli_stmt_bind_param($query, 'i', $idPengguna);

    mysqli_stmt_execute($query);

    $pengguna = mysqli_fetch_assoc(mysqli_stmt_get_result($query));

    header('Cache-Control: no-store');

}

