<?php

include __DIR__ . '/../includes/app.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    exit('Gunakan form balasan pada halaman detail film.');
}
if (!$pengguna) {
    header('Location: ../login.php?error=expired');
    exit;
}
$csrf = $_POST['csrf'] ?? null;
if (!is_string($csrf) || !isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $csrf)) {
    http_response_code(403);
    exit('Form kedaluwarsa. Muat ulang halaman dan coba lagi.');
}

$idFilm = filter_input(INPUT_POST, 'film_id', FILTER_VALIDATE_INT);
$idUlasan = filter_input(INPUT_POST, 'ulasan_id', FILTER_VALIDATE_INT);
$parent = filter_input(INPUT_POST, 'parent_id', FILTER_VALIDATE_INT);
$isi = is_string($_POST['isi'] ?? '') ? trim($_POST['isi'] ?? '') : '';
if (!$idFilm || $idFilm < 1 || !$idUlasan || $idUlasan < 1 || $parent === null || $parent === false || $parent < 0) {
    http_response_code(400);
    exit('Tujuan balasan tidak valid. Kembali ke detail film.');
}

$query = mysqli_prepare($konek, 'SELECT UlasanID FROM ulasan WHERE UlasanID = ? AND Movie_Api_ID = ?');
if (!$query) {
    http_response_code(500);
    exit('Tabel ulasan belum bisa dibaca. Periksa database ulasanweb.');
}
mysqli_stmt_bind_param($query, 'ii', $idUlasan, $idFilm);
if (!mysqli_stmt_execute($query)) {
    http_response_code(500);
    exit('Ulasan belum bisa diperiksa. Silakan coba lagi.');
}
$hasil = mysqli_stmt_get_result($query);
if (mysqli_num_rows($hasil) == 0) {
    http_response_code(404);
    exit('Ulasan tidak ditemukan untuk film ini.');
}

if ($parent > 0) {
    $query = mysqli_prepare($konek, 'SELECT BalasanID FROM balasan WHERE BalasanID = ? AND UlasanID = ?');
    if (!$query) {
        http_response_code(500);
        exit('Tabel balasan belum bisa dibaca. Periksa database ulasanweb.');
    }
    mysqli_stmt_bind_param($query, 'ii', $parent, $idUlasan);
    if (!mysqli_stmt_execute($query)) {
        http_response_code(500);
        exit('Balasan induk belum bisa diperiksa. Silakan coba lagi.');
    }
    $hasil = mysqli_stmt_get_result($query);
    if (mysqli_num_rows($hasil) == 0) {
        http_response_code(400);
        exit('Balasan induk tidak sesuai dengan ulasan ini.');
    }
} else {
    $parent = null;
}

if ($isi == '' || !mb_check_encoding($isi, 'UTF-8') || mb_strlen($isi) > 5000) {
    $_SESSION['error_ulasan'] = 'Tulis balasan 1–5000 karakter.';
} else {
    $idPengguna = (int)$pengguna['UsersID'];
    $query = mysqli_prepare($konek, 'INSERT INTO balasan (UlasanID, UserID, ParentID, Isi) VALUES (?, ?, ?, ?)');
    if (!$query) {
        http_response_code(500);
        exit('Tabel balasan belum siap. Periksa database ulasanweb.');
    }
    mysqli_stmt_bind_param($query, 'iiis', $idUlasan, $idPengguna, $parent, $isi);
    if (mysqli_stmt_execute($query)) {
        $_SESSION['pesan'] = 'Balasan berhasil dikirim.';
    } else {
        $_SESSION['error_ulasan'] = 'Balasan gagal dikirim. Silakan coba lagi.';
    }
}
$halaman = filter_input(INPUT_POST, 'halaman', FILTER_VALIDATE_INT) ?: 1;
$halaman = max(1, $halaman);
header('Location: ../detail.php?id=' . $idFilm . '&page=' . $halaman . '#ulasan-' . $idUlasan);
exit;
