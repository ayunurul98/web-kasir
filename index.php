    <?php include "koneksi.php"; ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Program Kasir Sederhana</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
    <div class="container mt-5">
        <h3 class="text-center mb-4">Kasir Toko Sederhana</h3>
        
        <form id="formKasir">   
            <div class="form-group">
                <label>Pilih Barang</label>
                <select class="form-control" name="kode_barang" id="kode_barang" required>
                    <option value="">-- Pilih Barang --</option>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM tb_barang");
                    while ($d = mysqli_fetch_assoc($res)) {
                        echo "<option value='{$d['Kode_Barang']}' data-harga='{$d['Harga']}'>{$d['Kode_Barang']} - {$d['Nama_Barang']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Harga</label>
                <input type="text" class="form-control" id="harga" name="harga" readonly>
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" class="form-control" name="jumlah" id="jumlah" required>
            </div>
            <button type="submit" class="btn btn-success">Tambah ke Keranjang</button>
        </form>

        <h4 class="mt-4">Daftar Belanja</h4>
        <table class="table table-bordered" id="tabelBelanja">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <div class="form-group mt-3">
            <label>Uang Dibayar</label>
            <input type="number" class="form-control" id="dibayar" placeholder="Masukkan jumlah uang dibayar" required>
        </div>
        <div class="text-right mb-3">
            <button class="btn btn-primary" id="btnBayar">Bayar</button>
        </div>

        <div class="mt-4" id="strukOutput"></div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    let keranjang = [];

    function renderTabel() {
        let tbody = $('#tabelBelanja tbody');
        tbody.html('');
        keranjang.forEach((item, index) => {
            tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.kode}</td>
                    <td>${item.nama}</td>
                    <td>Rp ${item.harga.toLocaleString()}</td>
                    <td>${item.jumlah}</td>
                    <td>Rp ${(item.harga * item.jumlah).toLocaleString()}</td>
                    <td><button class="btn btn-danger btn-sm" onclick="hapusItem(${index})">Hapus</button></td>
                </tr>
            `);
        });
    }

    function hapusItem(index) {
        keranjang.splice(index, 1);
        renderTabel();
    }

    $(function(){
        $('#kode_barang').change(function(){
            let harga = $(this).find(':selected').data('harga');
            $('#harga').val(harga);
        });

        $('#formKasir').submit(function(e){
            e.preventDefault();
            let kode   = $('#kode_barang').val();
            let nama   = $('#kode_barang option:selected').text().split(' - ')[1];
            let harga  = parseInt($('#harga').val());
            let jumlah = parseInt($('#jumlah').val());

            if (!kode || !harga || !jumlah) {
                alert("Isi semua data barang.");
                return;
            }

            keranjang.push({
                kode,
                nama,
                harga,
                jumlah
            });

            renderTabel();
            $('#formKasir')[0].reset();
            $('#harga').val('');
        });

        $('#btnBayar').click(function(){
            if (keranjang.length === 0) {
                alert("Keranjang masih kosong!");
                return;
            }

            let dibayar = parseInt($('#dibayar').val());
            if (isNaN(dibayar) || dibayar <= 0) {
                alert("Masukkan jumlah uang dibayar dengan benar.");
                return;
            }

            $.ajax({
                url: 'proses.php',
                method: 'POST',
                data: { 
                    keranjang: JSON.stringify(keranjang), 
                    dibayar: dibayar 
                },
                success: function(res) {
                    $('#strukOutput').html(res);
                    keranjang = [];
                    renderTabel();
                    $('#dibayar').val('');
                }
            });
        });
    });
    </script>
    </body>
    </html>
