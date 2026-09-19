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
    <style>
        :root {
            --bg-color: #f8fafc;
            --surface-color: #ffffff;
            --border-color: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --primary: #2563eb;
            --danger-bg: #fef2f2;
            --danger-border: #fecaca;
            --danger-text: #991b1b;
            --warning-bg: #fffbeb;
            --warning-border: #fde68a;
            --warning-text: #92400e;
            --success-bg: #f0fdf4;
            --success-border: #bbf7d0;
            --success-text: #166534;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            line-height: 1.5;
            padding: 32px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            margin-bottom: 28px;
        }

        .brand-subtitle {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 4px;
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .desc {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }

        .stat-card {
            background-color: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 18px 20px;
        }

        .stat-label {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            margin-bottom: 6px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            font-variant-numeric: tabular-nums;
        }

        .stat-card.alert {
            background-color: #fffaf0;
            border-color: #feebc8;
        }

        .stat-card.alert .stat-value {
            color: var(--warning-text);
        }

        .table-card {
            background-color: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
        }

        .table-header-bar {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 1rem;
            font-weight: 600;
        }

        .table-subtitle {
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
            text-align: left;
        }

        th {
            background-color: #f8fafc;
            color: var(--text-secondary);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        tbody tr.status-kritis {
            background-color: var(--warning-bg);
        }

        tbody tr.status-habis {
            background-color: var(--danger-bg);
        }

        .col-center {
            text-align: center;
        }

        .col-right {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        .code-id {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.8125rem;
            color: var(--text-secondary);
            background: #f1f5f9;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .product-name {
            font-weight: 600;
            color: var(--text-primary);
        }

        .category-badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 4px;
            background-color: #f1f5f9;
            color: #475569;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 4px;
            white-space: nowrap;
        }

        .badge-aman {
            background-color: var(--success-bg);
            color: var(--success-text);
            border: 1px solid var(--success-border);
        }

        .badge-kritis {
            background-color: var(--warning-bg);
            color: var(--warning-text);
            border: 1px solid var(--warning-border);
        }

        .badge-habis {
            background-color: var(--danger-bg);
            color: var(--danger-text);
            border: 1px solid var(--danger-border);
        }

        .product-desc {
            color: var(--text-secondary);
            font-size: 0.8125rem;
            max-width: 300px;
            line-height: 1.4;
        }

        tfoot td {
            background-color: #f8fafc;
            font-weight: 600;
            border-bottom: none;
            padding: 14px 16px;
        }

        .footer-note {
            margin-top: 16px;
            font-size: 0.8125rem;
            color: var(--text-muted);
            text-align: right;
        }
    </style>
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
                <div>
                    <div class="table-title">Daftar Komoditas Produk</div>
                    <div class="table-subtitle">Baris berlatar kuning/merah menandakan stok perlu restock segera</div>
                </div>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th class="col-center">ID</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th class="col-right">Harga Satuan</th>
                            <th class="col-right">Stok</th>
                            <th class="col-right">Total Nilai</th>
                            <th class="col-center">Status</th>
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
                                <td class="col-center">
                                    <span class="code-id"><?= htmlspecialchars($item['id']) ?></span>
                                </td>
                                <td>
                                    <span class="product-name"><?= htmlspecialchars($item['nama']) ?></span>
                                </td>
                                <td>
                                    <span class="category-badge"><?= htmlspecialchars($item['kategori']) ?></span>
                                </td>
                                <td class="col-right"><?= formatRupiah($item['harga']) ?></td>
                                <td class="col-right"><strong><?= $item['stok'] ?></strong></td>
                                <td class="col-right"><?= formatRupiah($subtotal) ?></td>
                                <td class="col-center">
                                    <?php if ($item['stok'] === 0): ?>
                                        <span class="badge badge-habis">Habis</span>
                                    <?php elseif (isStokKritis($item['stok'])): ?>
                                        <span class="badge badge-kritis">Kritis</span>
                                    <?php else: ?>
                                        <span class="badge badge-aman">Tersedia</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="product-desc"><?= htmlspecialchars($item['deskripsi']) ?></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="col-right">Total Nilai Aset Keseluruhan:</td>
                            <td class="col-right"><?= formatRupiah($totalAset) ?></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>

        <div class="footer-note">
            Arsitektur: Data Layer (products.php) | Processing Layer (functions.php) | Presentation Layer (index.php)
        </div>
    </div>
</body>
</html>
