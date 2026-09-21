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

---

## 2. Cetak Biru Komponen 1: Data Layer (`products.php`)

### Peran dan Tanggung Jawab
Menjadi basis data komoditas produk di tingkat memori (in-memory data storage) menggunakan struktur multidimensional array. Data layer tidak boleh mengandung logika pemrosesan kalkulasi maupun sintaks tampilan HTML.

### Kamus Data (Data Dictionary)

| Atribut | Tipe Data | Keterangan Logis | Aturan Validasi | Contoh Nilai |
| :--- | :--- | :--- | :--- | :--- |
| `id` | String | Pengenal unik setiap komoditas produk | Format kode prefix PRD-XXX | `PRD-001` |
| `nama` | String | Nama lengkap komoditas dagang | Teks deskriptif, tidak boleh kosong | `Beras Pandan Wangi 5kg` |
| `kategori` | String | Pengelompokan jenis barang | Sembako, Minuman, Bumbu Dapur | `Sembako` |
| `harga` | Integer | Nilai jual per unit komoditas (satuan Rupiah) | Bilangan bulat positif (> 0) | `78500` |
| `stok` | Integer | Jumlah unit fisik yang tersedia di gudang | Bilangan bulat non-negatif (>= 0) | `14` |
| `deskripsi` | String | Ringkasan spesifikasi komoditas | Penjelasan tekstual ringkas produk | `Beras aroma pandan alami` |

### Rancangan Struktur Multidimensional Array
Struktur data dirancang berupa Indexed Array yang membungkus kumpulan Associative Array. Setiap elemen array mewakili satu entitas produk utuh:

```
$products = [
    [
        'id'        => (string) identifier unik,
        'nama'      => (string) nama komoditas,
        'kategori'  => (string) kategori komoditas,
        'harga'     => (integer) harga satuan dalam rupiah,
        'stok'      => (integer) kuantitas fisik tersedia,
        'deskripsi' => (string) deskripsi spesifikasi
    ],
    ...
]
```

### Skenario Variasi Data Uji (Test Cases)
Untuk memastikan sistem dapat mengevaluasi kondisi secara menyeluruh, data komoditas harus mencakup tiga skenario:
1. Skenario Stok Aman: Kuantitas stok >= 3 (misal: 14 unit, 25 unit).
2. Skenario Stok Kritis: Kuantitas stok antara 1 sampai 2 unit (memicu deteksi stok tipis).
3. Skenario Stok Habis: Kuantitas stok tepat 0 unit (memicu status barang kosong).

---

## 3. Cetak Biru Komponen 2: Processing Layer (`functions.php`)

### Peran dan Tanggung Jawab
Menjadi pusat logika pemrosesan data (business logic). Berkas ini mengisolasi seluruh kalkulasi dan evaluasi kondisional agar tidak tercampur dengan struktur data atau kode antarmuka.

### Rancangan Fungsi 1: hitungTotalNilaiStok()

#### Tujuan
Mengalkulasi total nilai kapital atau aset seluruh komoditas yang tersimpan di dalam gudang secara otomatis berdasarkan perkalian harga satuan dan kuantitas stok masing-masing produk.

#### Rumus Matematis
Total Nilai Aset = Jumlah Kumulatif (Harga Produk ke-i dikali Stok Produk ke-i) untuk i = 1 sampai n.

#### Spesifikasi Input dan Output
- Input Parameter: `$products` (Array Multidimensi berisi daftar produk).
- Tipe Data Kembalian: Integer (Nilai total aset akumulatif).

#### Pseudocode Algoritma
```text
ALGORITMA hitungTotalNilaiStok(products):
    DEKLARASI:
        totalAset SEBAGAI INTEGER
        item SEBAGAI ASSOCIATIVE ARRAY
    
    DESKRIPSI:
        totalAset <- 0
        UNTUK SETIAP item DALAM products LAKUKAN:
            totalAset <- totalAset + (item['harga'] * item['stok'])
        AKHIR UNTUK
        
        KEMBALIKAN totalAset
AKHIR ALGORITMA
```

### Rancangan Fungsi 2: Logika Kondisional Stok Kritis (< 3)

#### Aturan Bisnis (Business Rules)
Ambang batas aman ketersediaan barang di gudang ditetapkan minimal 3 unit. Jika kuantitas stok produk berada di bawah 3 unit, sistem harus menyaring dan memberikan instruksi visual khusus pada baris tabel bersangkutan agar pengelola gudang dapat segera melakukan pengadaan ulang (restock).

#### Tabel Keputusan Kondisi Stok (Decision Matrix)

| Kondisi Kuantitas Stok | Status Logis | Kelas Visual Baris | Indikator Warna Visual |
| :--- | :--- | :--- | :--- |
| `stok == 0` | Habis | `status-habis` | Latar merah lembut (Red Alert) |
| `stok > 0 DAN stok < 3` | Kritis | `status-kritis` | Latar kuning/oranye lembut (Amber Warning) |
| `stok >= 3` | Tersedia | `status-aman` | Latar putih netral |

#### Pseudocode Evaluasi Kondisi
```text
FUNGSI isStokKritis(stok):
    JIKA stok < 3 MAKA:
        KEMBALIKAN BENAR
    SELAIN ITU:
        KEMBALIKAN SALAH
AKHIR FUNGSI

FUNGSI getBarisStokClass(stok):
    JIKA stok == 0 MAKA:
        KEMBALIKAN "status-habis"
    SELAIN JIKA isStokKritis(stok) MAKA:
        KEMBALIKAN "status-kritis"
    SELAIN ITU:
        KEMBALIKAN "status-aman"
AKHIR FUNGSI
```

### Rancangan Fungsi 3: Helper Format Representasi Moneter
Untuk memisahkan representasi angka mentah dari format mata uang, fungsi `formatRupiah(nominal)` disiapkan untuk mengubah bilangan bulat menjadi format mata uang resmi Indonesia (pemisah ribuan titik dengan prefiks `Rp`).

---

## 4. Cetak Biru Komponen 3: Presentation Layer (`index.php`)

### Peran dan Tanggung Jawab
Menjadi gerbang utama aplikasi (front controller) yang bertugas merajut komponen data dan pemrosesan, mengeksekusi iterasi antarmuka, serta merender keluaran akhir berupa dokumen HTML ke peramban pengguna.

### Mekanisme Integrasi Berkas via require_once
Presentation layer memanfaatkan instruksi `require_once` pada baris awal sebelum blok HTML dieksekusi:

1. `require_once 'products.php'`: Memuat array multidimensi `$products` ke dalam lingkup variabel lokal.
2. `require_once 'functions.php'`: Memuat pustaka fungsi pemrosesan (`hitungTotalNilaiStok`, `isStokKritis`, dll).

#### Mengapa Menggunakan require_once?
- Integritas Dependensi: Sistem tidak boleh berjalan jika data atau fungsi tidak tersedia. Pernyataan `require_once` akan menghentikan eksekusi skrip (Fatal Error) jika berkas dependensi hilang, berbeda dengan `include` yang hanya memicu peringatan (Warning).
- Pencegahan Redeklarasi: Kata kunci `_once` menjamin berkas fungsi atau data tidak dimuat berulang kali, sehingga terhindar dari galat fatal deklarasi fungsi ganda (Cannot redeclare function).

### Perancangan Rendering Tabel HTML via foreach
Data array produk diiterasi secara sekuensial menggunakan struktur kontrol `foreach ($products as $item)`. Setiap elemen array dipetakan secara terstruktur ke dalam tag baris `<tr>` dan kolom `<td>`:

```text
ALGORITMA RenderTabel(products):
    CETAK "<table> ... <tbody>"
    UNTUK SETIAP item DALAM products LAKUKAN:
        kelasBaris <- getBarisStokClass(item['stok'])
        subtotal   <- item['harga'] * item['stok']
        
        CETAK "<tr class='" + kelasBaris + "'>"
        CETAK "  <td>" + item['id'] + "</td>"
        CETAK "  <td>" + item['nama'] + "</td>"
        CETAK "  <td>" + item['kategori'] + "</td>"
        CETAK "  <td>" + formatRupiah(item['harga']) + "</td>"
        CETAK "  <td>" + item['stok'] + "</td>"
        CETAK "  <td>" + formatRupiah(subtotal) + "</td>"
        CETAK "  <td>" + getLabelStok(item['stok']) + "</td>"
        CETAK "  <td>" + item['deskripsi'] + "</td>"
        CETAK "</tr>"
    AKHIR UNTUK
    CETAK "</tbody> ... </table>"
AKHIR ALGORITMA
```

### Mockup Wireframe Antarmuka Konseptual

```
+-----------------------------------------------------------------------------------------+
| PRODUCT INFORMATION SYSTEM                                                              |
| Modul Praktikum Pemrograman Web - Pertemuan 2                                           |
+-----------------------------------------------------------------------------------------+
| [Total Jenis: 8 Item] | [Total Stok: 72 Unit] | [Total Aset: Rp X.XXX.XXX] | [Kritis: 3]|
+-----------------------------------------------------------------------------------------+
| ID      | NAMA PRODUK         | KATEGORI  | HARGA    | STOK | SUB TOTAL | STATUS  | KET |
+---------+---------------------+-----------+----------+------+-----------+---------+-----+
| PRD-001 | Beras Pandan 5kg    | Sembako   | 78.500   |  14  | 1.099.000 | Aman    | ... |
| PRD-002 | Minyak Goreng 2L    | Sembako   | 34.000   |   2  |    68.000 | Kritis* | ... | (Baris Kuning)
| PRD-004 | Kopi Lampung 250g   | Minuman   | 32.000   |   1  |    32.000 | Kritis* | ... | (Baris Kuning)
| PRD-006 | Susu UHT 1L         | Minuman   | 21.000   |   0  |         0 | Habis*  | ... | (Baris Merah)
+---------+---------------------+-----------+----------+------+-----------+---------+-----+
| FOOTER  | Total Nilai Aset Keseluruhan:              | Rp 2.153.500                       |
+---------+--------------------------------------------+------------------------------------+
```

---

## 5. Matriks Alur Data Antar Berkas (Data Flow Matrix)

Tabel berikut menunjukkan siklus hidup dan transformasi data dari sumber mentah hingga ditampilkan ke antarmuka pengguna:

| Tahapan | Lokasi Berkas | Aksi Logis | Input | Output Logis |
| :--- | :--- | :--- | :--- | :--- |
| Inisialisasi Data | `products.php` | Mendefinisikan entitas komoditas | Nilai mentah komoditas | Multidimensional array `$products` |
| Kalkulasi Aset | `functions.php` | Menghitung akumulasi nilai kapital | Array `$products` | Bilangan integer total nilai aset |
| Filter Stok Kritis | `functions.php` | Mengevaluasi ambang batas stok | Integer nilai `stok` | Boolean isKritis dan nama kelas baris |
| Format Angka | `functions.php` | Standarisasi representasi moneter | Integer nominal | String berformat rupiah (`Rp ...`) |
| Integrasi Berkas | `index.php` | Menggabungkan data dan logika | Path berkas target | Ketersediaan variabel dan fungsi di scope lokal |
| Perulangan Render | `index.php` | Ekstraksi array ke elemen baris | Array `$products` | Baris `<tr>` tabel HTML dengan warna kondisional |
| Penyajian Akhir | `index.php` | Pengiriman berkas lengkap ke klien | Dokumen HTML terstruktur | Tampilan halaman web di peramban pengguna |

---

## 6. Kesimpulan Perancangan

Perancangan ini membuktikan bahwa tanpa perlu langsung mengetik kode implementasi, seluruh cetak biru logis dari sistem telah matang:
1. Batasan tanggung jawab antar berkas terdefinisi dengan jelas (Separation of Concerns).
2. Struktur data telah siap menampung berbagai variasi kasus nyata di lapangan.
3. Alur algoritma dan logika percabangan kondisional telah terverifikasi secara matematis dan prosedural.
4. Integrasi dependensi terstruktur dengan aman menggunakan mekanisme `require_once`.
