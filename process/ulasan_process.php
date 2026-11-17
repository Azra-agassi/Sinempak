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

$idFilm = filter_input(INPUT_POST, 'film_id', FILTER_VALIDATE_INT);
if (!$idFilm || $idFilm < 1) exit('ID film tidak valid.');
$isi = is_string($_POST['ulasan'] ?? '') ? trim($_POST['ulasan'] ?? '') : '';
$rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
if (!$rating || $rating < 1 || $rating > 5 || $isi == '' || mb_strlen($isi) > 5000) {
    $_SESSION['error_ulasan'] = 'Pilih rating 1–5 dan tulis ulasan 1–5000 karakter.';
} else {
    $idPengguna = (int)$pengguna['UsersID'];
    $query = mysqli_prepare($konek, 'SELECT UlasanID FROM ulasan WHERE Ulasan_UserID = ? AND Movie_Api_ID = ?');
    mysqli_stmt_bind_param($query, 'ii', $idPengguna, $idFilm);
    mysqli_stmt_execute($query);
    $lama = mysqli_fetch_assoc(mysqli_stmt_get_result($query));
    if ($lama) {
        $query = mysqli_prepare($konek, 'UPDATE ulasan SET Rating = ?, UlasanUsers = ?, create_at = CURRENT_TIMESTAMP WHERE UlasanID = ?');
        mysqli_stmt_bind_param($query, 'isi', $rating, $isi, $lama['UlasanID']);
    } else {
        $query = mysqli_prepare($konek, 'INSERT INTO ulasan (Ulasan_UserID, Movie_Api_ID, Rating, UlasanUsers) VALUES (?, ?, ?, ?)');
        mysqli_stmt_bind_param($query, 'iiis', $idPengguna, $idFilm, $rating, $isi);
    }
    if (mysqli_stmt_execute($query)) $_SESSION['pesan'] = 'Ulasan berhasil disimpan.';
    else $_SESSION['error_ulasan'] = 'Ulasan gagal disimpan. Silakan coba lagi.';
}
header('Location: ../detail.php?id=' . $idFilm . '#bagian-ulasan');
exit;
