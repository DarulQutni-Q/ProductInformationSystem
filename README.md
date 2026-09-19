# Product Information System

Mini project sistem informasi manajemen data komoditas produk dan estimasi nilai aset gudang berbasis PHP dengan arsitektur 3 layer:

## Struktur Arsitektur

1. **Data Layer (`products.php`)**
   Menyimpan data komoditas dalam multidimensional array dengan atribut ID, Nama, Kategori, Harga, Stok, dan Deskripsi.

2. **Processing Layer (`functions.php`)**
   Berisi fungsi kalkulasi aset gudang (`hitungTotalNilaiStok`) serta logika conditional untuk menyaring status dan warna baris jika stok kritis (< 3).

3. **Presentation Layer (`index.php`)**
   Mengintegrasikan layer data dan proses menggunakan `require_once`, lalu merender ringkasan metrik dan tabel HTML melalui perulangan `foreach`.

## Cara Menjalankan

Jalankan built-in web server PHP dari direktori project:

```bash
php -S localhost:8000
```

Buka browser dan akses alamat `http://localhost:8000`.
