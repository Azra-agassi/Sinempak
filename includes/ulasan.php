<?php

$idFilm = filter_var($idFilm ?? null, FILTER_VALIDATE_INT);
$idUlasan = filter_var($idUlasan ?? null, FILTER_VALIDATE_INT);
$parentID = filter_var($parentID ?? null, FILTER_VALIDATE_INT);
$page = max(1, (int)($page ?? 1));
$csrf = $_SESSION['csrf'] ?? '';

if (!$idFilm || $idFilm < 1 || !$idUlasan || $idUlasan < 1 || $parentID === false || $parentID < 0 || !is_string($csrf) || $csrf === '') {
    http_response_code(400);
    exit('Buka detail film dari dashboard untuk menulis balasan.');
}
?>

<details class="mt-2">
    <summary>Balas</summary>

    <form action="process/balasan_process.php" method="post" class="mt-2">
        <input type="hidden" name="film_id"
               value="<?php echo $idFilm; ?>">

        <input type="hidden" name="ulasan_id"
               value="<?php echo $idUlasan; ?>">

        <input type="hidden" name="parent_id"
               value="<?php echo $parentID; ?>">

        <input type="hidden" name="halaman"
               value="<?php echo $page; ?>">

        <input type="hidden" name="csrf"
               value="<?php echo htmlspecialchars($csrf); ?>">

        <label class="form-label"
               for="balas-<?php echo $idUlasan . '-' . $parentID; ?>">
            Isi balasan
        </label>

        <textarea
            class="form-control mb-2"
            id="balas-<?php echo $idUlasan . '-' . $parentID; ?>"
            name="isi"
            rows="2"
            maxlength="5000"
            required
        ></textarea>

        <button class="btn btn-primary btn-sm" type="submit">
            Kirim balasan
        </button>
    </form>
</details>