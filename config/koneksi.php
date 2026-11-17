<?php
mysqli_report(MYSQLI_REPORT_OFF);
$konek = @mysqli_connect("localhost","root","","ulasanweb");
if (!$konek) {
    exit('Koneksi database gagal. Pastikan MySQL di XAMPP sudah berjalan.');
}
mysqli_set_charset($konek, 'utf8mb4');
