<?php
session_start();
include "../lib/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$id_produk = $_GET['id'] ?? null;
$jumlah = $_GET['jumlah'] ?? 1;

if (!$id_produk) {
    echo "Produk tidak ditemukan.";
    exit();
}

$sql = "SELECT * FROM histori_diskon WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_produk]);
$produk = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produk) {
    echo "Produk tidak tersedia.";
    exit();
}

$total_harga = $produk['harga_setelah_diskon'] * $jumlah;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi | Zenith Bags</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #561C24;
            --secondary-color: #EFECEC;
        }

        body {
            background-color: var(--secondary-color);
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 800px;
            margin-top: 50px;
        }

        .card {
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background-color: var(--primary-color);
            color: #fff;
            font-weight: bold;
        }

        .btn-custom {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 8px;
        }

        .btn-custom:hover {
            background-color: #3e151b;
        }
        .back-button {
            margin-top: 20px;
            display: inline-block;
            background-color: #561C24;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin-left: 40px;
            margin-bottom: 10px;
        }

        .back-button:hover {
            background-color: #3e151c;
        }

    </style>
</head>

<body>
<div class="container">
        <div class="card">
            <div class="card-header text-center">
                Konfirmasi Transaksi
            </div>
            <div class="card-body">
                <center>
                    <img src="../uploads/<?= htmlspecialchars($produk['foto_produk']); ?>" class="card-img-top w-25">
                    <h5 class="card-title mt-2"><?= htmlspecialchars($produk['nama_barang']); ?></h5>
                    <p class="card-text">
                        <strong>Rp. <?= number_format($produk['harga_setelah_diskon'], 0, ',', '.'); ?></strong>
                    </p>
                </center>


                <form method="post" action="proses_transaksi.php" class="mt-4">
                    <input type="hidden" name="id_produk" value="<?= $id_produk; ?>">

                    <div class="mb-3">
                        <label for="jumlah" class="form-label"><b>Jumlah</b></label>
                        <input type="number" id="jumlah" name="jumlah" class="form-control"
                            value="<?= htmlspecialchars($jumlah); ?>" min="1" max="<?= $produk['stok']; ?>" required>
                        <div class="form-text">Stok tersedia: <?= $produk['stok']; ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label"><b>Alamat Pengiriman</b></label>
                        <textarea class="form-control" name="alamat" id="alamat" rows="3" placeholder="Masukkan alamat lengkap..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="no_telp" class="form-label"><b>Nomor Telepon</b></label>
                        <input type="tel" class="form-control" name="no_telp" id="no_telp" placeholder="masukan no telp anda" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><b>Metode Pembayaran</b></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metode_pembayaran" id="cod" value="COD" checked>
                            <label class="form-check-label" for="cod">Cash on Delivery (COD)</label>
                        </div>
                    </div>

                    <h5 class="text-danger"><b>Total:</b> <span id="total-harga">Rp. <?= number_format($total_harga, 0, ',', '.'); ?></span></h5>
                    <button type="submit" class="btn btn-custom w-100 mt-3">Bayar Sekarang</button>
                </form>
            </div>
        </div>
    </div>
    <a href="index.php" class="back-button"> ← Kembali</a>
    <script>
        const jumlahInput = document.getElementById('jumlah');
        const hargaSatuan = <?= $produk['harga_setelah_diskon']; ?>;
        const totalHargaSpan = document.getElementById('total-harga');

        jumlahInput.addEventListener('input', () => {
            let jumlah = parseInt(jumlahInput.value) || 0;
            let total = hargaSatuan * jumlah;
            totalHargaSpan.textContent = 'Rp. ' + total.toLocaleString('id-ID');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
