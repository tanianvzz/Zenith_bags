<?php
include "../lib/koneksi.php";

if (!isset($_GET['id'])) {
    echo "<p>ID tidak ditemukan!</p>";
    exit();
}
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM histori_diskon WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "<p>Data tidak ditemukan!</p>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_barang = $_POST['nmbrng'];
    $harga = floatval($_POST['bil1']);
    $diskon = floatval($_POST['bil2']);
    $stok = floatval($_POST['stok']);
    $foto_lama = $data['foto_produk'];
    $foto_baru = $_FILES['foto_produk'];

    $harga_setelah_diskon = $harga - ($diskon / 100 * $harga);

    $folder = "../uploads/";
    $file_name = $foto_lama;

    if (!empty($foto_baru['name'])) {
        $file_name = time() . "_" . basename($foto_baru['name']);
        $target_file = $folder . $file_name;

        if (move_uploaded_file($foto_baru['tmp_name'], $target_file)) {
            if (!empty($foto_lama) && file_exists($folder . $foto_lama)) {
                unlink($folder . $foto_lama);
            }
        } else {
            echo "<p style='color:red;'>Gagal mengupload gambar baru!</p>";
            exit();
        }
    }

    $update = $conn->prepare("UPDATE histori_diskon 
                              SET nama_barang=?, harga=?, diskon=?, harga_setelah_diskon=?, stok=?, foto_produk=? 
                              WHERE id=?");
    if ($update->execute([$nama_barang, $harga, $diskon, $harga_setelah_diskon, $stok, $file_name, $id])) {
        echo "<p style='color:green;'>Data berhasil diperbarui!</p>";
        echo "<script>setTimeout(() => window.location.href='dashboard.php', 2000);</script>";
    } else {
        echo "<p style='color:red;'>Gagal memperbarui data!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk Diskon</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        padding: 20px;
    }

    .container-fluid {
        background: white;
        max-width: 90%;
        margin: auto;
        padding: 20px;
        margin-bottom:20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        color: #561C24;
    }

    label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
    }

    input {
        width: 98%;
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    button {
        background: #561C24;
        color: white;
        border: none;
        padding: 10px;
        margin-top: 15px;
        width: 100%;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background: #3e151c;
    }

    img {
        margin-top: 10px;
        max-width: 100px;
        border-radius: 5px;
    }

    .back-button {
        right: 20px;
        padding: 10px 20px;
        background-color: #561C24;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
        margin-top:10px;
    }

    .back-button:hover {
        background-color: #3e151c;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>Edit Produk Diskon</h1>
        <form method="POST" enctype="multipart/form-data">
            <label>Nama Barang</label>
            <input type="text" name="nmbrng" value="<?= htmlspecialchars($data['nama_barang']) ?>" required />

            <label>Masukkan Harga</label>
            <input type="number" name="bil1" value="<?= $data['harga'] ?>" required />

            <label>Masukkan Diskon (%)</label>
            <input type="number" name="bil2" value="<?= $data['diskon'] ?>" required max="100" />

            <label>Stok</label>
            <input type="number" name="stok" value="<?= $data['stok'] ?>" required />

            <label>Foto Produk Saat Ini</label><br>
            <?php if (!empty($data['foto_produk'])): ?>
            <img src="../uploads/<?= $data['foto_produk'] ?>" alt="Foto Produk">
            <?php else: ?>
            <p class="text-muted">Belum ada gambar</p>
            <?php endif; ?>

            <label>Upload Foto Baru (opsional)</label>
            <input type="file" name="foto_produk" />

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
    <div class="mt-5 h-5">
        <a href="dashboard.php" class="back-button">Batal Edit</a>
    </div>
</body>

</html>