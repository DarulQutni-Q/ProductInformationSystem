<?php
require_once 'products.php';
require_once 'functions.php';

$totalAset = hitungTotalNilaiStok($products);
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
            <h1>Product Information System</h1>
            <p>Sistem inventaris komoditas produk dan monitoring aset gudang.</p>
        </header>

        <section class="table-card">
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
