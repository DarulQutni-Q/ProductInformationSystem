<?php

function hitungTotalNilaiStok(array $products): int
{
    $total = 0;
    foreach ($products as $product) {
        $total += ($product['harga'] * $product['stok']);
    }
    return $total;
}

function isStokKritis(int $stok): bool
{
    return $stok < 3;
}

function getBarisStokClass(int $stok): string
{
    if ($stok === 0) {
        return 'status-habis';
    }

    if (isStokKritis($stok)) {
        return 'status-kritis';
    }

    return 'status-aman';
}

function getLabelStok(int $stok): string
{
    if ($stok === 0) {
        return 'Habis';
    }

    if (isStokKritis($stok)) {
        return 'Kritis';
    }

    return 'Tersedia';
}

function formatRupiah(int|float $nominal): string
{
    return 'Rp ' . number_format($nominal, 0, ',', '.');
}
