# Cetak Biru Arsitektur: Product Information System

Dokumen perancangan cetak biru (blueprint) sistem informasi manajemen data komoditas produk berbasis konsep arsitektur tiga lapis (three-layer architecture).

## Informasi Modul Praktikum
- Modul: Pemrograman Web (Pertemuan 2)
- Topik: Mini Project 1: Product Information System (Desain)
- Tujuan: Merancang struktur blueprints sistem manajemen data informasi produk siap pakai berbasis konsep teori yang telah dipelajari.
- Catatan Sesi: Sesi tanpa coding / pengetikan kode, berfokus murni pada pematangan konsep arsitektur desain secara logis.

---

## 1. Konsep Arsitektur Desain Konseptual

Sistem dirancang menggunakan prinsip Separation of Concerns (pemisahan tanggung jawab) yang memecah aplikasi ke dalam tiga lapis independen namun saling terhubung:

1. Data Layer (`products.php`): Bertanggung jawab sebagai pusat persistensi dan representasi data statis komoditas produk dalam memori runtime.
2. Processing Layer (`functions.php`): Bertanggung jawab sebagai pusat pemrosesan logika bisnis, kalkulasi matematis nilai aset, serta evaluasi aturan kondisi stok.
3. Presentation Layer (`index.php`): Bertanggung jawab sebagai penyusun antarmuka akhir, merajut berkas dependensi, serta menyajikan data olahan ke dalam format visual tabel HTML.

### Diagram Hubungan Antar Lapis Logis

```
+-------------------------------------------------------------+
|                         DATA LAYER                          |
|                       (products.php)                        |
|   Menyimpan multidimensional array komoditas produk         |
+------------------------------+------------------------------+
                               |
                               | (aliran data produk)
                               v
+-------------------------------------------------------------+
|                      PROCESSING LAYER                       |
|                       (functions.php)                       |
|   1. hitungTotalNilaiStok() -> menghitung total aset        |
|   2. isStokKritis() -> evaluasi ambang batas stok < 3       |
+------------------------------+------------------------------+
                               |
                               | (data produk + hasil kalkulasi)
                               v
+-------------------------------------------------------------+
|                     PRESENTATION LAYER                      |
|                        (index.php)                          |
|   1. Merajut layer dengan require_once                      |
|   2. Merender tabel HTML via foreach                        |
|   3. Menerapkan penanda warna pada baris stok kritis        |
+-------------------------------------------------------------+
```
