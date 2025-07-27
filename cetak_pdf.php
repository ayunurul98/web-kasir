<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

if (isset($_POST['keranjang']) && isset($_POST['dibayar'])) {
    $keranjang = json_decode($_POST['keranjang'], true);
    $dibayar = intval($_POST['dibayar']);
    $grandTotal = 0;

    ob_start(); // Tangkap HTML-nya
    ?>
    <html>
    <head>
        <style>
            body {
                font-family: 'Courier New', monospace;
                font-size: 12px;
                margin: 0;
                padding: 10px;
            }
            h3 {
                text-align: center;
                margin-bottom: 10px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
            }
            th, td {
                border: 1px solid #000;
                padding: 4px;
                text-align: left;
            }
            tfoot td, tfoot th {
                font-weight: bold;
            }
            .center {
                text-align: center;
            }
            .right {
                text-align: right;
            }
        </style>
    </head>
    <body>
        <h3>STRUK PEMBAYARAN</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($keranjang as $item): 
                $nama   = htmlspecialchars($item['nama']);
                $harga  = intval($item['harga']);
                $jumlah = intval($item['jumlah']);
                $total  = $harga * $jumlah;
                $grandTotal += $total;
            ?>
                <tr>
                    <td><?= $nama ?></td>
                    <td class="right">Rp <?= number_format($harga, 0, ',', '.') ?></td>
                    <td class="center"><?= $jumlah ?></td>
                    <td class="right">Rp <?= number_format($total, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total Belanja</th>
                    <th class="right">Rp <?= number_format($grandTotal, 0, ',', '.') ?></th>
                </tr>
                <tr>
                    <th colspan="3">Dibayar</th>
                    <th class="right">Rp <?= number_format($dibayar, 0, ',', '.') ?></th>
                </tr>
                <tr>
                    <th colspan="3">Kembalian</th>
                    <th class="right">Rp <?= number_format($dibayar - $grandTotal, 0, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>

        <p class="center">
            <?= $dibayar >= $grandTotal 
                ? '<strong>Transaksi berhasil. Terima kasih!</strong>' 
                : '<strong style="color:red;">Uang tidak cukup. Transaksi gagal.</strong>' ?>
        </p>
    </body>
    </html>
    <?php
    $html = ob_get_clean();

    $pdf = new Dompdf();
    $pdf->loadHtml($html);
    $pdf->setPaper([0, 0, 400, 600], 'portrait'); // ukuran kecil seperti struk
    $pdf->render();
    $pdf->stream("struk_pembayaran.pdf", ["Attachment" => false]); // tampilkan di browser
    exit;
} else {
    echo "Data tidak lengkap.";
}
?>