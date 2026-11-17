<?php

function tmdb($alamat, $parameter = [])
{
    include __DIR__ . '/../config/configtmdb.php';
    if (!isset($parameter['language'])) $parameter['language'] = 'id-ID';
    $url = 'https://api.themoviedb.org/3/' . $alamat . '?' . http_build_query($parameter);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $apiKey]);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($curl, CURLOPT_TIMEOUT, 15);
    $isi = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $data = json_decode($isi ?: '', true);
    if ($status != 200 || !is_array($data)) return [];
    return $data;
}

function simpanJudul($id, $judul)
{
    $folder = __DIR__ . '/../storage/film';
    if (!is_dir($folder)) {
        if (!mkdir($folder, 0755, true) && !is_dir($folder)) {
            error_log('Folder penyimpanan judul film tidak bisa dibuat.');
            return;
        }
    }
    $file = $folder . '/' . (int)$id . '-judul.json';
    if (file_put_contents($file, json_encode(['data' => $judul], JSON_UNESCAPED_UNICODE)) === false) {
        error_log('Judul film tidak bisa disimpan.');
    }
}
function judulFilm($id)
{
    $file = __DIR__ . '/../storage/film/' . (int)$id . '-judul.json';
    if (is_file($file)) {
        $judul = json_decode(file_get_contents($file), true);
        if (!empty($judul['data'])) return $judul['data'];
    }
    $film = tmdb('movie/' . (int)$id);
    if (empty($film['title'])) return 'Judul film belum tersedia';
    simpanJudul($id, $film['title']);
    return $film['title'];
}
