<?php
require_once 'products.php';
require_once 'functions.php';

$totalAset = hitungTotalNilaiStok($products);
$totalProduk = count($products);
$totalStokKritis = 0;
$totalUnitStok = 0;

foreach ($products as $p) {
    $totalUnitStok += $p['stok'];
    if (isStokKritis($p['stok'])) {
        $totalStokKritis++;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Information System</title>
</head>
<body>
    <div class="container">
        <header>
            <div class="brand-subtitle">Mini Project 1 &bull; Modul Praktikum</div>
            <h1>Product Information System</h1>
            <p class="desc">Sistem inventaris komoditas produk dan kalkulasi nilai aset gudang.</p>
        </header>

        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Jenis Produk</div>
                <div class="stat-value"><?= $totalProduk ?> Item</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Kuantitas Stok</div>
                <div class="stat-value"><?= number_format($totalUnitStok, 0, ',', '.') ?> Unit</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Nilai Aset Gudang</div>
                <div class="stat-value"><?= formatRupiah($totalAset) ?></div>
            </div>
            <div class="stat-card <?= $totalStokKritis > 0 ? 'alert' : '' ?>">
                <div class="stat-label">Stok Kritis (&lt; 3)</div>
                <div class="stat-value"><?= $totalStokKritis ?> Produk</div>
            </div>
        </section>

        <section class="table-card">
            <div class="table-header-bar">
                <div class="table-title">Daftar Komoditas Produk</div>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga Satuan</th>
                            <th>Stok</th>
                            <th>Total Nilai</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $item): ?>
                            <?php
                                $rowClass = getBarisStokClass($item['stok']);
                                $statusLabel = getLabelStok($item['stok']);
                                $subtotal = $item['harga'] * $item['stok'];
                            ?>
                            <tr class="<?= $rowClass ?>">
                                <td><?= htmlspecialchars($item['id']) ?></td>
                                <td><?= htmlspecialchars($item['nama']) ?></td>
                                <td><?= htmlspecialchars($item['kategori']) ?></td>
                                <td><?= formatRupiah($item['harga']) ?></td>
                                <td><?= $item['stok'] ?></td>
                                <td><?= formatRupiah($subtotal) ?></td>
                                <td><?= $statusLabel ?></td>
                                <td><?= htmlspecialchars($item['deskripsi']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">Total Nilai Aset Keseluruhan:</td>
                            <td><?= formatRupiah($totalAset) ?></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
    </div>
</body>
</html>
