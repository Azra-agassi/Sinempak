<?php
include __DIR__ . '/includes/app.php';
if (!$pengguna) {
    header('Location: login.php?error=expired');
    exit;
}

$idPengguna = (int)$pengguna['UsersID'];
$query = mysqli_prepare($konek, 'SELECT COUNT(*) AS jumlah FROM ulasan WHERE Ulasan_UserID = ?');
mysqli_stmt_bind_param($query, 'i', $idPengguna);
mysqli_stmt_execute($query);
$hasil = mysqli_fetch_assoc(mysqli_stmt_get_result($query));
$jumlah = (int)$hasil['jumlah'];
$totalHalaman = max(1, (int)ceil($jumlah / 12));
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$page = max(1, min($totalHalaman, $page));
$offset = ($page - 1) * 12;

$query = mysqli_prepare($konek, 'SELECT UlasanID AS id, Movie_Api_ID AS film_id,
    Rating AS rating, UlasanUsers AS isi, create_at AS dibuat FROM ulasan
    WHERE Ulasan_UserID = ? ORDER BY create_at DESC, UlasanID DESC LIMIT 12 OFFSET ?');
mysqli_stmt_bind_param($query, 'ii', $idPengguna, $offset);
mysqli_stmt_execute($query);
$ulasanSaya = mysqli_stmt_get_result($query);
$nama = $pengguna['Name'] ?: $pengguna['Username'];
?>

<!doctype html>

<html lang="id" data-bs-theme="dark">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Profil Saya — Sinempak</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="assets/css/dashboard.css?v=6" rel="stylesheet">

    <link href="assets/css/profile.css" rel="stylesheet">

    <link href="assets/css/fonts.css?v=6" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">

    <a class="visually-hidden-focusable" href="#konten">Lewati navigasi</a>

    <nav class="navbar border-bottom">

        <div class="container py-2">

            <a class="navbar-brand fw-bold" href="dashboard.php">Sinempak</a>

            <div class="d-flex align-items-center flex-wrap gap-3 small">

                <a class="text-body-secondary text-decoration-none" href="dashboard.php">Kembali ke katalog</a>

                <a class="text-decoration-none" href="profile.php" aria-current="page">Profil saya</a>

                <form action="process/logout.php" method="post" class="m-0"><input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>"><button class="btn btn-outline-secondary btn-sm">Keluar</button></form>

            </div>

        </div>

    </nav>

    <main id="konten" class="container flex-grow-1 py-5">

        <p class="profile-eyebrow">RUANG PRIBADIMU</p>

        <section class="profile-hero mb-5" aria-labelledby="nama-profil">
            <div class="flex-grow-1">
                <h1 class="h2 mb-1" id="nama-profil"><?php echo htmlspecialchars($nama); ?></h1>
                <p class="text-body-secondary mb-0">@<?php echo htmlspecialchars($pengguna['Username']); ?></p>
            </div>
            <div class="profile-stat"><strong><?php echo $jumlah; ?></strong><span>Ulasan ditulis</span></div>
        </section>
        <?php if (isset($_SESSION['pesan'])) { ?>
            <p class="alert alert-success" role="status"><?php echo htmlspecialchars($_SESSION['pesan']); ?></p>
        <?php unset($_SESSION['pesan']);
        } ?>
        <div class="d-flex align-items-baseline justify-content-between mb-4 gap-3">
            <h2 class="h4 mb-0">Jejak tontonanmu</h2><span class="small text-body-secondary"><?php echo $jumlah; ?> ulasan</span>
        </div>
        <?php if (mysqli_num_rows($ulasanSaya) == 0) { ?>
            <div class="card card-body profile-empty text-center">
                <h3 class="h5">Cerita pertamamu menunggu.</h3>
                <p class="text-body-secondary">Pilih film, beri rating, dan bagikan pendapatmu.</p>
                <div><a class="btn btn-primary btn-sm" href="dashboard.php">Jelajahi film</a></div>
            </div>
        <?php } ?>
        <div class="row g-4">
            <?php while ($ulasan = mysqli_fetch_assoc($ulasanSaya)) { ?>
                <?php $ulasan['judul'] = judulFilm($ulasan['film_id']); ?>
                <div class="col-md-6">
                    <article class="card h-100 profile-review" data-review-id="<?php echo (int)$ulasan['id']; ?>">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between gap-3 mb-3"><time class="small text-body-secondary"><?php echo htmlspecialchars(date('d-m-Y', strtotime($ulasan['dibuat']))); ?></time><span class="text-warning small">★ <?php echo (int)$ulasan['rating']; ?>/5</span></div>
                            <h3 class="h5 mb-3"><a class="text-reset text-decoration-none" href="detail.php?id=<?php echo (int)$ulasan['film_id']; ?>"><?php echo htmlspecialchars($ulasan['judul']); ?></a></h3>
                            <p class="review-text profile-review-copy mb-4"><?php echo htmlspecialchars($ulasan['isi']); ?></p>
                            <div class="d-flex align-items-center justify-content-between gap-3 mt-auto pt-3 border-top">
                                <a class="small" href="detail.php?id=<?php echo (int)$ulasan['film_id']; ?>#bagian-ulasan">Lihat / perbarui</a>
                                <form action="process/ulasan_hapus.php" method="post" class="m-0" onsubmit="return confirm('Hapus ulasan ini beserta semua balasannya?');" data-film-title="<?php echo htmlspecialchars($ulasan['judul']); ?>">
                                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>"><input type="hidden" name="ulasan_id" value="<?php echo (int)$ulasan['id']; ?>">
                                    <button class="btn btn-outline-danger btn-sm" type="submit">Hapus ulasan</button>
                                </form>
                            </div>
                        </div>
                    </article>
                </div>
            <?php } ?>
        </div>
        <?php if ($totalHalaman > 1) { ?>
            <nav class="d-flex justify-content-center align-items-center gap-3 mt-4" aria-label="Halaman ulasan saya">
                <?php if ($page > 1) { ?><a class="btn btn-outline-secondary btn-sm" href="?page=<?php echo $page - 1; ?>">← Sebelumnya</a><?php } ?>
                <span class="small text-body-secondary"><?php echo $page; ?> / <?php echo $totalHalaman; ?></span>
                <?php if ($page < $totalHalaman) { ?><a class="btn btn-outline-secondary btn-sm" href="?page=<?php echo $page + 1; ?>">Berikutnya →</a><?php } ?>
            </nav>
        <?php } ?>
    </main>
    <footer class="border-top mt-auto">
        <div class="container py-4 small text-body-secondary"><span class="logo-situs">Sinempak</span> · Temukan tontonan. Bagikan sudut pandang.</div>
    </footer>

    <?php include __DIR__ . '/includes/session-timer.php'; ?>
</body>

</html>