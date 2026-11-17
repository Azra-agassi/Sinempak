function lihatPassword() {
    var kolom = document.getElementById('password');
    var tombol = document.getElementById('tombol-password');
    if (kolom.type == 'password') {
        kolom.type = 'text';
        tombol.textContent = 'Sembunyikan';
    } else {
        kolom.type = 'password';
        tombol.textContent = 'Lihat';
    }
    tombol.setAttribute('aria-pressed', kolom.type == 'text');
    tombol.setAttribute('aria-label', tombol.textContent + ' password');
}
