<?php
require_once 'products.php';
require_once 'functions.php';

$totalAset = hitungTotalNilaiStok($products);
$totalProduk = count($products);
$totalStokKritis = 0;
$totalUnitStok = 0;

foreach ($products as $item) {
    $totalUnitStok += $item['stok'];
    if (isStokKritis($item['stok'])) {
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <p class="subtitle">Praktikum Pemrograman Web</p>
            <h1>Sistem Informasi Produk</h1>
            <p class="desc">Manajemen inventaris barang dan monitoring aset gudang.</p>
        </header>

        <section class="summary-grid">
            <div class="summary-card">
                <span class="label">Total Jenis Produk</span>
                <span class="value"><?= $totalProduk ?> Item</span>
            </div>
            <div class="summary-card">
                <span class="label">Total Unit Fisik</span>
                <span class="value"><?= number_format($totalUnitStok, 0, ',', '.') ?> Unit</span>
            </div>
            <div class="summary-card">
                <span class="label">Total Nilai Aset</span>
                <span class="value"><?= formatRupiah($totalAset) ?></span>
            </div>
            <div class="summary-card alert">
                <span class="label">Perlu Restock (&lt; 3)</span>
                <span class="value"><?= $totalStokKritis ?> Produk</span>
            </div>
        </section>

        <section class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-right">Harga Satuan</th>
                        <th class="text-right">Stok</th>
                        <th class="text-right">Subtotal</th>
                        <th class="text-center">Status</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $item): ?>
                        <?php
                            $rowClass = getBarisStokClass($item['stok']);
                            $labelStok = getLabelStok($item['stok']);
                            $subtotal = $item['harga'] * $item['stok'];
                        ?>
                        <tr class="<?= $rowClass ?>">
                            <td class="text-center"><span class="code"><?= htmlspecialchars($item['id']) ?></span></td>
                            <td class="product-title"><?= htmlspecialchars($item['nama']) ?></td>
                            <td><span class="tag"><?= htmlspecialchars($item['kategori']) ?></span></td>
                            <td class="text-right"><?= formatRupiah($item['harga']) ?></td>
                            <td class="text-right num"><?= $item['stok'] ?></td>
                            <td class="text-right num"><?= formatRupiah($subtotal) ?></td>
                            <td class="text-center">
                                <span class="badge <?= $rowClass ?>"><?= $labelStok ?></span>
                            </td>
                            <td class="desc-text"><?= htmlspecialchars($item['deskripsi']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right">Total Nilai Aset Gudang:</td>
                        <td class="text-right num"><?= formatRupiah($totalAset) ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </section>
    </div>
</body>
</html>
