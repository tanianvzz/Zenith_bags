<?php
session_start();
include "../lib/koneksi.php";

// Cek jika user belum login atau bukan role user
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

// Ambil data produk dari histori diskon
$sql = "SELECT id, nama_barang, harga, diskon, harga_setelah_diskon, stok, foto_produk FROM histori_diskon ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$produk_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung jumlah item dalam keranjang
$user_id = $_SESSION['id_user'] ?? null;
$jumlah_item = 0;
if ($user_id && isset($_SESSION['keranjang'][$user_id])) {
    $jumlah_item = array_sum(array_column($_SESSION['keranjang'][$user_id], 'jumlah'));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda | Zenith Bags</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    :root {
        --primary-color: #561C24;
        /* Warna utama gelap */
        --secondary-color: #EFECEC;
        /* Warna latar belakang utama */
        --navbar-color: #3E1A1D;
        /* Warna navbar yang sedikit lebih gelap dari warna utama */
        --button-hover-color: #3e151b;
        /* Warna tombol saat hover */
        --footer-color: #3E1A1D;
        /* Warna footer */
    }

    dy {
        background-color: var(--secondary-color);
        font-family: 'Segoe UI', sans-serif;
    }

    .navbar {
        background-color: var(--navbar-color);
        /* Ubah warna navbar */
    }

    .navbar-brand,
    .nav-link {
        color: #fff !important;
    }

    .nav-link:hover {
        color: #f1f1f1 !important;
        /* Warna saat hover navbar */
    }

    .hero-section {
        background-color: var(--primary-color);
        color: #fff;
        padding: 50px 0;
        text-align: center;
    }

    .hero-section h1 {
        font-size: 2.5rem;
    }

    .btn-custom {
        background-color: var(--primary-color);
        color: #fff;
        border-radius: 10px;
    }

    .btn-custom:hover {
        background-color: var(--button-hover-color);
    }

    .content {
        padding: 30px;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 10px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    .card img {
        height: 200px;
        object-fit: cover;
        border-radius: 10px 10px 0 0;
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        color: #511b22;
    }

    .card .price {
        font-size: 16px;
        color: #888;
    }

    .card .text-danger {
        font-size: 18px;
        font-weight: bold;
        color: #561C24 !important;
    }

    .btn-danger {
        background-color: #561C24;
        border: none;
    }

    .btn-danger:hover {
        background-color: #3e151b;
    }

    .badge-diskon {
        background-color: #a0001b;
        color: white;
        font-size: 14px;
        padding: 5px 10px;
        border-radius: 5px;
        width: 75px;
    }

    .footer {
        background-color: var(--footer-color);
        color: #fff;
        padding: 20px;
        text-align: center;
    }
    </style>
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="#">Zenith Bags</a>
                <div class="ms-auto d-flex align-items-center gap-3">
                    <a href="../logout.php" class="nav-link">Logout</a>
                </div>
            </div>
        </nav>

        <div class="hero-section">
            <div class="row">
                <div class="col-md-5">
                    <img src="../img/banner.png" alt="banner" class="product-image w-75">
                </div>
                <div class="col-md-7">
                    <h1>Selamat datang di Zenith Bags!</h1>
                    <p>Temukan berbagai pilihan tas berkualitas dengan harga terbaik</p>
                    <a href="#produk" class="btn btn-custom mt-3">Lihat Katalog Tas</a>
                </div>
            </div>
        </div>

        <div class="content" id="produk">
            <h3 class="text-center my-4 fw-bold">Katalog Produk</h3>
            <hr>
            <div class="row">
                <?php if (!empty($produk_list)): ?>
                <?php foreach ($produk_list as $produk): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="../uploads/<?= htmlspecialchars($produk['foto_produk']); ?>" class="card-img-top"
                            alt="Produk">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($produk['nama_barang']); ?></h5>
                            <?php if ($produk['diskon'] > 0): ?>
                            <span class="badge-diskon mt-2"><?= htmlspecialchars($produk['diskon']); ?>% OFF</span>
                            <s class="price">Rp. <?= number_format($produk['harga'], 0, ',', '.'); ?></s>
                            <?php endif; ?>
                            <strong class="price text-danger">Rp.
                                <?= number_format($produk['harga_setelah_diskon'], 0, ',', '.'); ?></strong>
                            <a href="transaksi.php?id=<?= urlencode($produk['id']); ?>&jumlah=1"
                                class="btn btn-success w-100 mt-3">Beli Sekarang</a>

                            <a href="detail_user.php?id=<?= urlencode($produk['id']); ?>"
                                class="btn btn-danger w-100 mt-1">Detail Produk</a>
                        </div>

                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p class="text-center">Tidak ada produk tersedia.</p>
                <?php endif; ?>
            </div>
        </div>

        <footer class="footer">
            <p>&copy; 2025 Zenith Bags - Semua hak cipta dilindungi</p>
        </footer>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>