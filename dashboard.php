<?php
include __DIR__ . '/includes/app.php';
if (!$pengguna) {
    header('Location: login.php?error=expired');
    exit;
}

$daftarGenre = ['28' => 'Action', '12' => 'Adventure', '16' => 'Animation', '35' => 'Comedy', '18' => 'Drama', '27' => 'Horror', '10749' => 'Romance', '878' => 'Science Fiction'];
$daftarUrutan = [
    'popularity.desc' => 'Populer',
    'vote_average.desc' => 'Rating tertinggi',
    'primary_release_date.desc' => 'Rilis terbaru'
];

$pencarian = is_string($_GET['q'] ?? '') ? trim($_GET['q'] ?? '') : '';
$halaman = max(1, min(500, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1));
$tahun = filter_input(INPUT_GET, 'tahun', FILTER_VALIDATE_INT);
if (!$tahun || $tahun < 1900 || $tahun > date('Y') + 1) $tahun = '';
$tahun = (string)$tahun;

$genre = filter_input(INPUT_GET, 'genre', FILTER_VALIDATE_INT);
if (!isset($daftarGenre[$genre])) $genre = '';
$genre = (string)$genre;

$rating = filter_input(INPUT_GET, 'rating', FILTER_VALIDATE_INT);
if (!in_array($rating, [6, 7, 8, 9])) $rating = '';

$rating = (string)$rating;
$urutan = is_string($_GET['urutan'] ?? '') ? ($_GET['urutan'] ?? '') : '';
if (!isset($daftarUrutan[$urutan])) $urutan = 'popularity.desc';

$parameter = ['page' => $halaman, 'include_adult' => 'false'];
if ($pencarian !== '') {
    $parameter['query'] = $pencarian;
    $data = tmdb('search/movie', $parameter);
    $parameterHalaman = ['q' => $pencarian];
} else {
    $parameter['sort_by'] = $urutan;
    if ($tahun !== '') $parameter['primary_release_year'] = $tahun;
    if ($genre !== '') $parameter['with_genres'] = $genre;
    if ($rating !== '') $parameter['vote_average.gte'] = $rating;
    $data = tmdb('discover/movie', $parameter);
    $parameterHalaman = ['tahun' => $tahun, 'genre' => $genre, 'rating' => $rating, 'urutan' => $urutan];
}

$film = $data['results'] ?? [];
$totalHalaman = max(0, min(500, (int) ($data['total_pages'] ?? 0)));
$pesanError = !$data ? 'Data TMDB belum bisa dimuat. Periksa konfigurasi token dan koneksi internet, lalu coba lagi.' : '';

$daftarUlasan = mysqli_query($konek, "SELECT u.UlasanID AS id,
    u.Movie_Api_ID AS film_id, u.Rating AS rating, u.UlasanUsers AS isi,
    p.Name AS nickname FROM ulasan u JOIN users p ON p.UsersID = u.Ulasan_UserID
    WHERE u.UlasanUsers <> '' ORDER BY u.create_at DESC, u.UlasanID DESC LIMIT 20");
foreach ($film as $item) simpanJudul($item['id'], $item['title']);
?>

<!doctype html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Temukan Film Favoritmu — Sinempak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/dashboard.css?v=6" rel="stylesheet">
    <link href="assets/css/fonts.css?v=6" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <a class="visually-hidden-focusable" href="#konten">Lewati navigasi</a>
    <nav class="navbar border-bottom">
        <div class="container py-2">
            <a class="navbar-brand fw-bold mb-0" href="dashboard.php">Sinempak</a>
            <div class="d-flex flex-wrap align-items-center gap-3 small">
                <a class="text-body-secondary text-decoration-none" href="profile.php">Profil · <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                <form action="process/logout.php" method="post" class="m-0">
                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">
                    <button class="btn btn-outline-secondary btn-sm" type="submit">Keluar</button>
                </form>
            </div>
        </div>
    </nav>
    <main id="konten" class="container flex-grow-1 pt-4">
        <section class="card card-body p-3 p-lg-4 mb-5 browse-panel" aria-label="Browse dan pencarian film">
            <p class="small fw-semibold text-primary mb-3">BROWSE BY</p>
            <div class="row g-4 align-items-start">
                <div class="col-lg-8">

                    <form action="dashboard.php#katalog" method="get">
                        <div class="row g-2">
                            <div class="col-6 col-md-3">
                                <label class="form-label" for="tahun">Tahun</label>
                                <select class="form-select form-select-sm" id="tahun" name="tahun">
                                    <option value="">Semua tahun</option>
                                    <?php for ($pilihanTahun = (int) date('Y') + 1; $pilihanTahun >= 2000; $pilihanTahun--) { ?>
                                        <option value="<?php echo $pilihanTahun; ?>" <?php echo $tahun === (string) $pilihanTahun ? 'selected' : ''; ?>><?php echo $pilihanTahun; ?></option>
                                   <?php } ?>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label" for="genre">Genre</label>
                                <select class="form-select form-select-sm" id="genre" name="genre">
                                    <option value="">Semua genre</option>
                                    <?php foreach ($daftarGenre as $idGenre => $namaGenre) { ?>
                                        <option value="<?php echo $idGenre; ?>" <?php echo $genre === (string) $idGenre ? 'selected' : ''; ?>><?php echo $namaGenre; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label" for="rating">Rating minimum</label>
                                <select class="form-select form-select-sm" id="rating" name="rating">
                                    <option value="">Semua rating</option>
                                    <?php foreach (['6', '7', '8', '9'] as $nilaiRating) { ?>
                                        <option value="<?php echo $nilaiRating; ?>" <?php echo $rating === $nilaiRating ? 'selected' : ''; ?>><?php echo $nilaiRating; ?> ke atas</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label" for="urutan">Urutan</label>
                                <select class="form-select form-select-sm" id="urutan" name="urutan">
                                    <?php foreach ($daftarUrutan as $nilaiUrutan => $namaUrutan) { ?>
                                        <option value="<?php echo $nilaiUrutan; ?>" <?php echo $urutan === $nilaiUrutan ? 'selected' : ''; ?>><?php echo $namaUrutan; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-primary btn-sm" type="submit">Terapkan</button>
                            <a class="btn btn-outline-secondary btn-sm" href="dashboard.php#katalog">Reset</a>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4">

                    <form action="dashboard.php#katalog" method="get" role="search">
                        <label class="form-label" for="q">Cari judul film</label>
                        <div class="input-group input-group-sm">
                            <input class="form-control" type="search" id="q" name="q" value="<?php echo htmlspecialchars($pencarian); ?>" placeholder="Contoh: Interstellar" required>
                            <button class="btn btn-primary px-3" type="submit" aria-label="Cari film" title="Cari film">
                                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <circle cx="10.5" cy="10.5" r="6.5"></circle>
                                    <path d="m16 16 5 5"></path>
                                </svg>
                            </button>
                        </div>
                        <p class="small text-body-secondary mb-0 mt-2">Pencarian judul terpisah dari filter browse.</p>
                    </form>
                </div>
            </div>
        </section>
        <div class="row g-4 mb-5">
            <aside id="ulasan" class="col-lg-4 col-xl-3">
                <span id="ulasan-terbaru"></span>
                <h2 class="h5 fw-semibold mb-3">Ulasan terbaru</h2>
                <?php if (mysqli_num_rows($daftarUlasan) == 0) { ?>
                    <div class="card card-body p-3">
                        <p class="fw-semibold mb-2">Belum ada ulasan.</p>
                        <p class="small text-body-secondary mb-0">Pilih film di katalog untuk melihat detail dan ulasannya.</p>
                    </div>
                <?php } ?>
                <?php while ($ulasan = mysqli_fetch_assoc($daftarUlasan)) { ?>
                    <?php
                    $ulasan['judul'] = judulFilm($ulasan['film_id']);
                    $kataUlasan = preg_split('/\s+/u', trim($ulasan['isi']), -1, PREG_SPLIT_NO_EMPTY);
                    $ulasanPanjang = count($kataUlasan) > 200;
                    ?>
                    <article class="card mb-3">
                        <div class="card-body p-3 text-break">
                            <p class="fw-semibold mb-1"><?php echo htmlspecialchars($ulasan['nickname']); ?></p>
                            <p class="small text-primary mb-2"><a href="detail.php?id=<?php echo (int) $ulasan['film_id']; ?>"><?php echo htmlspecialchars($ulasan['judul']); ?></a></p>
                            <p class="small text-warning mb-2">&#9733; <?php echo number_format((float) $ulasan['rating'], 1, ',', '.'); ?>/5</p>
                            <p class="review-text small mb-0"><?php echo nl2br(htmlspecialchars($ulasanPanjang ? implode(' ', array_slice($kataUlasan, 0, 200)) . '…' : $ulasan['isi'])); ?></p>
                            <a class="btn btn-outline-secondary btn-sm mt-2" href="detail.php?id=<?php echo (int) $ulasan['film_id']; ?>#bagian-ulasan">Lihat / balas ulasan</a>
                            <?php if ($ulasanPanjang) { ?>
                                <details class="small mt-2">
                                    <summary class="text-info">Baca selengkapnya</summary>
                                    <p class="review-text mt-2 mb-0"><?php echo htmlspecialchars(implode(' ', array_slice($kataUlasan, 200))); ?></p>
                                </details>
                            <?php } ?>
                        </div>
                    </article>
                <?php } ?>
            </aside>
            <section id="katalog" class="col-lg-8 col-xl-9" aria-labelledby="judul-katalog">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <h2 class="h5 fw-semibold mb-0" id="judul-katalog"><?php echo $pencarian !== '' ? 'Hasil pencarian' : 'Jelajahi film'; ?></h2>
                    <span class="small text-body-secondary"><?php echo count($film); ?> film di halaman ini</span>
                </div>
                <?php if ($pencarian !== '') { ?>
                    <p class="small text-body-secondary">Hasil untuk “<?php echo htmlspecialchars($pencarian); ?>”. <a href="dashboard.php#katalog">Kembali ke browse</a></p>
                <?php } ?>
                <?php if ($pesanError !== '') { ?>
                    <p class="alert alert-warning" role="alert"><?php echo htmlspecialchars($pesanError); ?></p>
                <?php } elseif (!$film) { ?>
                    <p class="card card-body text-body-secondary">Tidak ada film yang cocok. Coba judul atau filter lain.</p>
                <?php } ?>
                <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
                    <?php foreach ($film as $item) { ?>
                        <div class="col">
                            <article class="film-card card h-100 overflow-hidden">
                                <a class="d-block text-reset text-decoration-none" href="detail.php?id=<?php echo (int) $item['id']; ?>" aria-label="Detail <?php echo htmlspecialchars($item['title'] ?? 'Film'); ?>">
                                    <?php if (!empty($item['poster_path'])) { ?>

                                        <img class="poster" width="500" height="750" src="https://image.tmdb.org/t/p/w342<?php echo htmlspecialchars($item['poster_path']); ?>" alt="Poster <?php echo htmlspecialchars($item['title'] ?? 'Film'); ?>" loading="lazy">
                                    <?php } else { ?>
                                        <div class="poster-kosong d-flex align-items-center justify-content-center p-3 small text-body-secondary text-center">Poster belum tersedia</div>
                                    <?php } ?>
                                </a>
                                <div class="card-body p-3 d-flex flex-column">
                                    <h3 class="film-title fw-semibold mb-1"><a class="text-reset text-decoration-none" href="detail.php?id=<?php echo (int) $item['id']; ?>"><?php echo htmlspecialchars($item['title'] ?? 'Judul belum tersedia'); ?></a></h3>
                                    <p class="small text-body-secondary mb-3"><?php echo htmlspecialchars(substr($item['release_date'] ?? '', 0, 4)); ?></p>
                                    <div class="d-flex flex-wrap justify-content-between gap-1 mt-auto small">
                                        <span class="text-warning">&#9733; <?php echo number_format((float) ($item['vote_average'] ?? 0), 1, ',', '.'); ?>/10</span>
                                        <span class="text-body-secondary">TMDB</span>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php } ?>
                </div>
                <?php if ($pesanError === '' && $totalHalaman > 0) { ?>
                    <nav class="d-flex flex-wrap justify-content-center align-items-center gap-3 mt-4" aria-label="Halaman film">
                        <?php if ($halaman > 1) {
                           $parameterHalaman['page'] = $halaman - 1; ?>
                            <a class="btn btn-outline-secondary btn-sm" href="?<?php echo htmlspecialchars(http_build_query($parameterHalaman)); ?>#katalog">&larr; Sebelumnya</a>
                        <?php } ?>
                        <span class="small text-body-secondary">Halaman <?php echo $halaman; ?> dari <?php echo $totalHalaman; ?></span>
                        <?php if ($halaman < $totalHalaman) {
                           $parameterHalaman['page'] = $halaman + 1; ?>
                            <a class="btn btn-outline-secondary btn-sm" href="?<?php echo htmlspecialchars(http_build_query($parameterHalaman)); ?>#katalog">Berikutnya &rarr;</a>
                        <?php } ?>
                    </nav>
                <?php } ?>
            </section>
        </div>
    </main>
    <footer class="border-top mt-auto">
        <div class="container py-4 d-flex flex-column flex-md-row justify-content-between gap-3">
            <div>
               <p class="logo-situs fw-semibold mb-1">Sinempak</p>
                <p class="small text-body-secondary mb-0">Temukan tontonan. Bagikan sudut pandang.</p>
           </div>
            <p class="small text-body-secondary mb-0">Data film dari TMDB.<br>Produk ini tidak didukung atau disertifikasi oleh TMDB.</p>
        </div>
    </footer>
    <?php include __DIR__ . '/includes/session-timer.php'; ?>
</body>
</html>