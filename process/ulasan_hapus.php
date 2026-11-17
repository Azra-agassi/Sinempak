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

$id = filter_input(INPUT_POST, 'ulasan_id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) exit('ID ulasan tidak valid.');
$idPengguna = (int)$pengguna['UsersID'];

$query = mysqli_prepare($konek, 'SELECT UlasanID FROM ulasan WHERE UlasanID = ? AND Ulasan_UserID = ?');
mysqli_stmt_bind_param($query, 'ii', $id, $idPengguna);
mysqli_stmt_execute($query);
if (mysqli_num_rows(mysqli_stmt_get_result($query)) == 0) exit('Ulasan ini bukan milikmu.');

mysqli_begin_transaction($konek);
$query = mysqli_prepare($konek, 'DELETE FROM balasan WHERE UlasanID = ?');
mysqli_stmt_bind_param($query, 'i', $id);
$balasanDihapus = mysqli_stmt_execute($query);
$query = mysqli_prepare($konek, 'DELETE FROM ulasan WHERE UlasanID = ? AND Ulasan_UserID = ?');
mysqli_stmt_bind_param($query, 'ii', $id, $idPengguna);
if ($balasanDihapus && mysqli_stmt_execute($query)) {
    mysqli_commit($konek);
    $_SESSION['pesan'] = 'Ulasan dan balasannya berhasil dihapus.';
} else {
    mysqli_rollback($konek);
    $_SESSION['pesan'] = 'Ulasan gagal dihapus. Silakan coba lagi.';
}
header('Location: ../profile.php');
exit;
