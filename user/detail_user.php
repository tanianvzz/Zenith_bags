<?php
include "../lib/koneksi.php";

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID tidak valid.";
    exit;
}

$id = $_GET['id'];

// Ambil data produk
$sql = "SELECT id, nama_barang, harga, diskon, harga_setelah_diskon, stok, foto_produk FROM histori_diskon WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$produk = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika tidak ditemukan
if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Produk - <?= htmlspecialchars($produk['nama_barang']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f9f9f9;
        padding: 40px;
        font-family: 'Segoe UI', sans-serif;
    }

    .product-container {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 30px;
        max-width: 1000px;
        margin: auto;
    }

    .product-image {
        width: 100%;
        height: auto;
        border-radius: 10px;
        object-fit: cover;
        max-height: 450px;
    }

    .price-original {
        text-decoration: line-through;
        color: gray;
        font-size: 16px;
    }

    .price-discount {
        color: #561C24;
        font-size: 28px;
        font-weight: bold;
    }

    .badge-diskon {
        background-color: #a0001b;
        color: white;
        font-size: 14px;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .btn-buy {
        background-color: #561C24;
        color: white;
        font-size: 18px;
        padding: 12px;
        width: 100%;
        border-radius: 8px;
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
    .btn-buy:hover {
        background-color: #a0001b;
    }
    </style>
</head>

<body>
    <div class="product-container row">
        <div class="col-md-6">
            <img src="../uploads/<?= htmlspecialchars($produk['foto_produk']); ?>" alt="Foto Produk"
                class="product-image w-100">
        </div>
        <div class="col-md-6">
            <h2><?= htmlspecialchars($produk['nama_barang']); ?></h2>
            <div class="mb-2">
                <?php if ($produk['diskon'] > 0): ?>
                    <span class="badge-diskon mt-2"><?= htmlspecialchars($produk['diskon']); ?>% OFF</span>
                    <s class="price">Rp. <?= number_format($produk['harga'], 0, ',', '.'); ?></s>
                    <?php endif; ?>
                </div>
                <div class=" price-discount">
                    Rp <?= number_format($produk['harga_setelah_diskon'], 0, ',', '.'); ?>
                </div>
                <p>Stok tersedia: <strong><?= htmlspecialchars($produk['stok']); ?></strong></p>
            <div class="button mt-5 d-grid gap-2">
                <a href="transaksi.php?id=<?= $produk['id']; ?>" class="btn btn-success">
                    Beli Sekarang
                </a>
            </div>

        </div>

    </div>
   <a href="index.php" class="back-button"> ← Kembali</a>
</body>

</html>