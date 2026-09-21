# Rancangan Sistem Informasi Produk (Mini Project 1)

Tugas perancangan konsep arsitektur untuk sistem manajemen data informasi produk pada mata kuliah Pemrograman Web (Pertemuan 2). Sesuai arahan praktikum, sesi ini berfokus pada cetak biru (blueprint) logis tanpa pengetikan kode implementasi.

## Konsep Pembagian Layer

Sistem ini dibagi menjadi tiga bagian utama agar pengelolaan data, proses perhitungan, dan tampilan antarmuka terpisah secara rapi:

1. Data Layer (`products.php`): tempat menyimpan data mentah produk.
2. Processing Layer (`functions.php`): kumpulan fungsi untuk memproses data dan logika kondisi.
3. Presentation Layer (`index.php`): halaman utama yang memanggil data dan fungsi, lalu menampilkannya ke tabel HTML.

---

## 1. Data Layer (`products.php`)

Data produk disimpan dalam bentuk array multidimensi (array di dalam array). Array utama bertindak sebagai daftar produk, sedangkan setiap elemen di dalamnya berupa associative array yang menyimpan informasi detail per barang:

- `id`: kode unik produk (misalnya PRD-001)
- `nama`: nama barang komoditas (misalnya Beras Pandan Wangi 5kg)
- `kategori`: kelompok barang (misalnya Sembako atau Minuman)
- `harga`: harga satuan produk dalam bentuk angka integer (misalnya 78500)
- `stok`: jumlah barang yang ada di gudang (misalnya 14)
- `deskripsi`: penjelasan singkat mengenai produk

Contoh gambaran strukturnya:

```php
$products = [
    [
        'id' => 'PRD-001',
        'nama' => 'Beras Pandan Wangi 5kg',
        'kategori' => 'Sembako',
        'harga' => 78500,
        'stok' => 14,
        'deskripsi' => 'Beras pulen kualitas premium kemasan 5kg.'
    ],
    [
        'id' => 'PRD-002',
        'nama' => 'Minyak Goreng Sawit 2L',
        'kategori' => 'Sembako',
        'harga' => 34000,
        'stok' => 2,
        'deskripsi' => 'Minyak kelapa sawit kemasan pouch.'
    ]
];
```

Pada data ini disiapkan juga barang dengan stok di bawah 3 untuk menguji logika peringatan stok kritis.

---

## 2. Processing Layer (`functions.php`)

Berkas ini memuat logika bisnis agar file tampilan (`index.php`) tetap bersih dan tidak tercampur rumus perhitungan:

### a. Perhitungan Total Nilai Aset Gudang
Fungsi `hitungTotalNilaiStok($products)` digunakan untuk menghitung total nilai uang dari seluruh barang yang ada di gudang.
- Logika: melakukan perulangan pada seluruh data produk, mengalikan `harga * stok` untuk setiap barang, lalu menjumlahkannya ke satu variabel penampung total.
- Output: angka total nilai aset dalam satuan rupiah.

### b. Pengecekan Stok Kritis (< 3)
Kondisi ini digunakan untuk menyaring barang yang stoknya sudah menipis agar pengelola gudang tahu barang mana yang harus segera dipesan ulang:
- Jika stok bernilai 0: status barang habis dan baris tabel diberi penanda khusus (misalnya warna merah).
- Jika stok kurang dari 3: status barang kritis dan baris tabel diberi penanda peringatan (misalnya warna kuning).
- Jika stok 3 atau lebih: status aman dan baris tabel ditampilkan normal.

Selain itu, disiapkan juga fungsi pembantu sederhana untuk mengubah format angka biasa menjadi format rupiah (misalnya `Rp 78.500`) saat ditampilkan ke pengguna.

---

## 3. Presentation Layer (`index.php`)

Berkas ini berfungsi sebagai halaman web yang dilihat oleh pengguna:

1. Pemanggilan Komponen (`require_once`):
   Di bagian paling atas, file `products.php` dan `functions.php` dimuat menggunakan perintah `require_once`. Alasan menggunakan `require_once` adalah untuk memastikan data dan fungsi benar-benar tersedia sebelum halaman dimuat, serta mencegah error jika file tidak sengaja terpanggil lebih dari satu kali.

2. Menampilkan Data ke Tabel HTML (`foreach`):
   Setelah data dan fungsi berhasil dimuat, tabel HTML dibuat untuk menampilkan daftar barang. Data dari array `$products` diulang baris demi baris menggunakan perulangan `foreach`:
   - Setiap putaran loop akan membuat satu baris `<tr>`.
   - Di dalam loop, logika kondisi stok dipanggil untuk menentukan class warna baris tabel.
   - Kolom tabel (`<td>`) diisi dengan ID, nama produk, kategori, harga, sisa stok, subtotal nilai per barang, status, dan deskripsi.
   - Di baris paling bawah tabel (`<tfoot>`), hasil dari fungsi `hitungTotalNilaiStok()` ditampilkan sebagai total aset keseluruhan.
