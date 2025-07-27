<?php
if (isset($_POST['keranjang']) && isset($_POST['dibayar'])) {
    $keranjang = json_decode($_POST['keranjang'], true);
    $dibayar = intval($_POST['dibayar']);
    $grandTotal = 0;

    echo "<h5>Struk Pembayaran</h5>";
    echo "<table class='table table-sm table-bordered'>";
    echo "<thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
          </thead><tbody>";

    foreach ($keranjang as $item) {
        $nama   = htmlspecialchars($item['nama']);
        $harga  = intval($item['harga']);
        $jumlah = intval($item['jumlah']);
        $total  = $harga * $jumlah;
        $grandTotal += $total;

        echo "<tr>
                <td>$nama</td>
                <td>Rp " . number_format($harga, 0, ',', '.') . "</td>
                <td>$jumlah</td>
                <td>Rp " . number_format($total, 0, ',', '.') . "</td>
              </tr>";
    }

    echo "</tbody>
          <tfoot>
            <tr>
                <th colspan='3'>Total Belanja</th>
                <th>Rp " . number_format($grandTotal, 0, ',', '.') . "</th>
            </tr>
            <tr>
                <th colspan='3'>Dibayar</th>
                <th>Rp " . number_format($dibayar, 0, ',', '.') . "</th>
            </tr>";

    if ($dibayar < $grandTotal) {
        echo "<tr><td colspan='4' class='text-danger text-center'><strong>Uang tidak cukup! Transaksi dibatalkan.</strong></td></tr>";
    } else {
        $kembalian = $dibayar - $grandTotal;
        echo "<tr>
                <th colspan='3'>Kembalian</th>
                <th>Rp " . number_format($kembalian, 0, ',', '.') . "</th>
              </tr>";
    }

    echo "</tfoot></table>";

    if ($dibayar >= $grandTotal) {
    echo "<form action='cetak_pdf.php' method='post' target='_blank'>";
    echo "<input type='hidden' name='keranjang' value='" . htmlspecialchars(json_encode($keranjang), ENT_QUOTES) . "'>";
    echo "<input type='hidden' name='dibayar' value='$dibayar'>";
    echo "<button type='submit' class='btn btn-primary'>Cetak PDF</button>";
    echo "</form>";
    }
    else {
        echo "<div class='alert alert-danger text-right'><strong>Transaksi gagal. Silakan bayar sesuai total belanja.</strong></div>";
    }

} else {
    echo "<div class='alert alert-danger'>Data tidak lengkap!</div>";
}
?>
