<?php
include __DIR__ . '/includes/app.php';

$idFilm = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$idFilm || $idFilm < 1) {
    http_response_code(400);
    exit('ID film tidak valid. Pilih film dari dashboard.');
}
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

$film = tmdb('movie/' . $idFilm, ['append_to_response' => 'credits,videos', 'include_video_language' => 'id,en']);
$trailer = '';
$genre = [];
if ($film) {
    simpanJudul($idFilm, $film['title']);
    if (empty($film['overview'])) {
        $filmInggris = tmdb('movie/' . $idFilm, ['language' => 'en-US']);
        $film['overview'] = $filmInggris['overview'] ?? '';
    }
    foreach ($film['genres'] ?? [] as $item) $genre[] = $item['name'];
    foreach ($film['videos']['results'] ?? [] as $video) {
        if ($video['site'] == 'YouTube' && $video['type'] == 'Trailer' && preg_match('/^[a-zA-Z0-9_-]{11}$/', $video['key'])) {
            $trailer = $video['key'];
            break;
        }
    }
}

$query = mysqli_prepare($konek, 'SELECT COUNT(*) AS jumlah FROM ulasan WHERE Movie_Api_ID = ?');
mysqli_stmt_bind_param($query, 'i', $idFilm);
mysqli_stmt_execute($query);
$baris = mysqli_fetch_assoc(mysqli_stmt_get_result($query));
$jumlah = (int)$baris['jumlah'];
$totalHalaman = max(1, (int)ceil($jumlah / 10));
$page = max(1, min($page, $totalHalaman));
$offset = ($page - 1) * 10;
$query = mysqli_prepare($konek, 'SELECT u.*, p.Name FROM ulasan u JOIN users p ON p.UsersID = u.Ulasan_UserID
    WHERE u.Movie_Api_ID = ? ORDER BY u.create_at DESC, u.UlasanID DESC LIMIT 10 OFFSET ?');
mysqli_stmt_bind_param($query, 'ii', $idFilm, $offset);
mysqli_stmt_execute($query);
$daftarUlasan = mysqli_stmt_get_result($query);

$ulasanSaya = null;
if ($pengguna) {
    $idPengguna = (int)$pengguna['UsersID'];
    $query = mysqli_prepare($konek, 'SELECT Rating, UlasanUsers FROM ulasan WHERE Ulasan_UserID = ? AND Movie_Api_ID = ?');
    mysqli_stmt_bind_param($query, 'ii', $idPengguna, $idFilm);
    mysqli_stmt_execute($query);
    $ulasanSaya = mysqli_fetch_assoc(mysqli_stmt_get_result($query));
}
?>
<!doctype html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($film['title'] ?? 'Detail film'); ?> — Sinempak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/fonts.css?v=6" rel="stylesheet">
    <link href="assets/css/detail.css?v=6" rel="stylesheet">
</head>
<body>
    <header class="brand-header">
        <nav class="detail-container d-flex align-items-center justify-content-between gap-3">
            <a class="navbar-brand" href="dashboard.php">Sinempak<span>.</span></a>
            <div class="d-flex align-items-center gap-4"><a class="back-link" href="profile.php">Profil saya</a><a class="back-link" href="dashboard.php">← Kembali ke katalog</a></div>
        </nav>
    </header>
    <main id="konten">
        <?php if (!$film) { ?>
            <div class="detail-container film-status"><h1 class="h3">Data film belum bisa dimuat.</h1><p>Periksa koneksi internet atau token TMDB, lalu coba lagi.</p><a class="btn btn-primary" href="?id=<?php echo $idFilm; ?>">Coba lagi</a></div>
        <?php } else { ?>
            <?php if (!empty($film['backdrop_path'])) { ?>
                <div class="movie-banner" aria-hidden="true"><img src="https://image.tmdb.org/t/p/w1280<?php echo htmlspecialchars($film['backdrop_path']); ?>" alt=""><div class="banner-fade"></div></div>
            <?php } ?>
            <div class="detail-container film-content <?php if (empty($film['backdrop_path'])) echo 'without-backdrop'; ?>">
                <section class="film-intro">
                    <?php if (!empty($film['poster_path'])) { ?><img class="movie-poster" src="https://image.tmdb.org/t/p/w500<?php echo htmlspecialchars($film['poster_path']); ?>" alt="Poster <?php echo htmlspecialchars($film['title']); ?>" width="220" height="330"><?php } ?>
                    <div class="film-heading">
                        <p class="eyebrow">PILIH TONTONAN. TEMUKAN CERITA.</p>
                        <h1 class="movie-title"><?php echo htmlspecialchars($film['title']); ?></h1>
                        <p class="movie-meta"><?php echo htmlspecialchars(substr($film['release_date'] ?? '', 0, 4) . ' · ' . implode(', ', $genre) . ' · ' . ($film['runtime'] ?? 0) . ' menit'); ?></p>
                        <div class="film-facts">
                            <p class="movie-score">★ <strong><?php echo number_format($film['vote_average'], 1); ?></strong><span class="score-scale">/10 TMDB</span></p>
                            <p class="release-date">Tanggal rilis <time><?php echo htmlspecialchars($film['release_date'] ?? 'Belum tersedia'); ?></time></p>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <?php if ($trailer != '') { ?><a class="btn btn-primary" href="https://www.youtube.com/watch?v=<?php echo htmlspecialchars($trailer); ?>" target="_blank" rel="noopener noreferrer">▶ Tonton trailer</a><?php } ?>
                            <a class="btn btn-outline-secondary" href="#bagian-ulasan">Baca ulasan</a>
                        </div>
                    </div>
                </section>
                <section class="film-section"><p class="eyebrow">TENTANG FILM</p><h2 class="section-title">Di balik ceritanya</h2><p class="synopsis"><?php echo htmlspecialchars($film['overview'] ?: 'Sinopsis belum tersedia.'); ?></p></section>
                <section class="film-section">
                    <div class="section-heading"><h2 class="section-title">Pemeran utama</h2><span class="section-note">Wajah di balik karakter</span></div>
                    <ul class="cast-grid list-unstyled mb-0">
                        <?php foreach (array_slice($film['credits']['cast'] ?? [], 0, 4) as $pemeran) { ?>
                            <li class="cast-item">
                                <?php if (!empty($pemeran['profile_path'])) { ?><img src="https://image.tmdb.org/t/p/w185<?php echo htmlspecialchars($pemeran['profile_path']); ?>" alt="<?php echo htmlspecialchars($pemeran['name']); ?>" width="48" height="48" loading="lazy"><?php } ?>
                                <div><p class="cast-name fw-semibold"><?php echo htmlspecialchars($pemeran['name']); ?></p><p class="cast-role"><?php echo htmlspecialchars($pemeran['character']); ?></p></div>
                            </li>
                        <?php } ?>
                    </ul>
                </section>
            </div>
        <?php } ?>
        <section class="detail-container reviews-section" id="bagian-ulasan">
            <div class="section-heading"><div><p class="eyebrow">SUARA KOMUNITAS</p><h2 class="section-title">Ulasan Pengguna (<?php echo $jumlah; ?>)</h2></div><span class="community-label">Dari penonton Sinempak</span></div>
            <?php if (isset($_SESSION['pesan'])) { ?><p class="alert alert-success" role="status"><?php echo htmlspecialchars($_SESSION['pesan']); ?></p><?php unset($_SESSION['pesan']); } ?>
            <?php if (isset($_SESSION['error_ulasan'])) { ?><p class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_SESSION['error_ulasan']); ?></p><?php unset($_SESSION['error_ulasan']); } ?>
            <div class="reviews-layout">
                <div class="review-results">
                    <p class="review-status <?php if ($jumlah > 0) echo 'has-reviews'; ?>"><?php echo $jumlah ? 'Pendapat dari penonton Sinempak.' : 'Belum ada ulasan. Jadilah yang pertama membagikan pendapatmu.'; ?></p>
                    <?php while ($ulasan = mysqli_fetch_assoc($daftarUlasan)) { ?>
                        <article class="review-article" id="ulasan-<?php echo $ulasan['UlasanID']; ?>">
                            <div class="review-heading"><div><h3 class="review-name fw-semibold"><?php echo htmlspecialchars($ulasan['Name']); ?></h3><time class="review-date"><?php echo date('d-m-Y', strtotime($ulasan['create_at'])); ?></time></div><span class="review-score">★ <?php echo $ulasan['Rating']; ?>/5</span></div>
                            <?php if (mb_strlen($ulasan['UlasanUsers']) > 700) { ?>
                                <details><summary>Baca ulasan lengkap</summary><p class="review-text"><?php echo htmlspecialchars($ulasan['UlasanUsers']); ?></p></details>
                            <?php } else { ?><p class="review-text"><?php echo htmlspecialchars($ulasan['UlasanUsers']); ?></p><?php } ?>
                            <?php
                            $idUlasan = (int)$ulasan['UlasanID'];
                            $parentID = 0;
                            if ($pengguna) include __DIR__ . '/includes/ulasan.php';
                            $query = mysqli_prepare($konek, 'SELECT b.*, p.Name FROM balasan b JOIN users p ON p.UsersID = b.UserID WHERE b.UlasanID = ? ORDER BY b.BalasanID');
                            mysqli_stmt_bind_param($query, 'i', $idUlasan);
                            mysqli_stmt_execute($query);
                            $daftarBalasan = mysqli_stmt_get_result($query);
                            $tingkat = [];
                            $penulis = [];
                            while ($balasan = mysqli_fetch_assoc($daftarBalasan)) {
                                $induk = (int)$balasan['ParentID'];
                                $tingkat[$balasan['BalasanID']] = $induk ? ($tingkat[$induk] ?? 0) + 1 : 0;
                                $penulis[$balasan['BalasanID']] = $balasan['Name'];

                                $jarak = min(3, $tingkat[$balasan['BalasanID']]) * 12;
                            ?>
                                <div class="balasan-ulasan" style="margin-left: <?php echo $jarak; ?>px">
                                    <p class="fw-bold small mb-1"><?php echo htmlspecialchars($balasan['Name']); ?></p>
                                    <?php if ($induk) { ?><p class="small text-body-secondary mb-1">Membalas <?php echo htmlspecialchars($penulis[$induk] ?? 'balasan sebelumnya'); ?></p><?php } ?>
                                    <time class="review-date"><?php echo date('d-m-Y', strtotime($balasan['Dibuat'])); ?></time>
                                    <p class="balasan-isi small mb-1"><?php echo htmlspecialchars($balasan['Isi']); ?></p>
                                    <?php $parentID = (int)$balasan['BalasanID']; if ($pengguna) include __DIR__ . '/includes/ulasan.php'; ?>
                                </div>
                            <?php } ?>
                        </article>
                    <?php } ?>
                    <?php if ($totalHalaman > 1) { ?>
                        <nav class="d-flex align-items-center gap-3 mt-4">
                            <?php if ($page > 1) { ?><a class="btn btn-outline-secondary" href="?id=<?php echo $idFilm; ?>&page=<?php echo $page - 1; ?>#bagian-ulasan">← Sebelumnya</a><?php } ?>
                            <span><?php echo $page . ' / ' . $totalHalaman; ?></span>
                            <?php if ($page < $totalHalaman) { ?><a class="btn btn-outline-secondary" href="?id=<?php echo $idFilm; ?>&page=<?php echo $page + 1; ?>#bagian-ulasan">Berikutnya →</a><?php } ?>
                        </nav>
                    <?php } ?>
                </div>
                <aside class="review-compose">
                    <p class="eyebrow">GILIRANMU BERCERITA</p><h3 class="h5 mb-2">Bagaimana filmnya?</h3><p class="small text-body-secondary mb-4">Bagikan pendapatmu untuk membantu penonton lain.</p>
                    <?php if (!$pengguna) { ?><p class="small"><a href="login.php">Masuk</a> untuk menulis ulasan atau balasan.</p><?php } ?>
                    <form action="process/ulasan_process.php" method="post" class="review-form">
                        <input type="hidden" name="film_id" value="<?php echo $idFilm; ?>">
                        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">
                        <fieldset <?php if (!$pengguna) echo 'disabled'; ?>>
                            <label class="form-label" for="rating">Rating kamu</label>
                            <select class="form-select mb-3" id="rating" name="rating" required>
                                <?php $pilihanRating = [5 => '★★★★★ — Sangat bagus', 4 => '★★★★☆ — Bagus', 3 => '★★★☆☆ — Cukup', 2 => '★★☆☆☆ — Kurang', 1 => '★☆☆☆☆ — Buruk']; ?>
                                <?php foreach ($pilihanRating as $nilai => $teks) { ?><option value="<?php echo $nilai; ?>" <?php if ($nilai == ($ulasanSaya['Rating'] ?? 5)) echo 'selected'; ?>><?php echo $teks; ?></option><?php } ?>
                            </select>
                            <label class="form-label" for="ulasan">Ulasan kamu</label>
                            <textarea class="form-control" id="ulasan" name="ulasan" rows="5" maxlength="5000" placeholder="Apa yang paling berkesan? Hindari spoiler, ya." required><?php echo htmlspecialchars($ulasanSaya['UlasanUsers'] ?? ''); ?></textarea>
                            <p class="form-hint">Satu ulasan per film. Kamu bisa memperbaruinya kapan saja.</p>
                            <button class="btn btn-primary w-100" type="submit"><?php echo $ulasanSaya ? 'Perbarui ulasan' : 'Kirim ulasan'; ?></button>
                        </fieldset>
                    </form>
                </aside>
            </div>
        </section>
    </main>
    <footer class="detail-footer"><div class="detail-container d-flex flex-wrap justify-content-between gap-2"><span><span class="logo-situs">Sinempak<span class="text-primary">.</span></span> &nbsp; Temukan tontonan. Bagikan sudut pandang.</span><span>Data film dari TMDB · Ulasan komunitas Sinempak</span></div></footer>
    <?php include __DIR__ . '/includes/session-timer.php'; ?>
</body>
</html>
