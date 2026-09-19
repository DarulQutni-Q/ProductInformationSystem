<?php

function hitungTotalNilaiStok(array $products): int
{
    $total = 0;
    foreach ($products as $product) {
        $total += ($product['harga'] * $product['stok']);
    }
    return $total;
}
