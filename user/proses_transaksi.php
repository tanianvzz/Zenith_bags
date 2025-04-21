<?php
session_start();
include "../lib/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'] ?? null;
$id_produk = $_POST['id_produk'] ?? null;
$jumlah = (int) ($_POST['jumlah'] ?? 1);
$alamat = trim($_POST['alamat'] ?? '');
$no_telp = trim($_POST['no_telp'] ?? '');
$metode_pembayaran = $_POST['metode_pembayaran'] ?? 'COD';

if (!$id_user || !$id_produk || $jumlah < 1 || !$alamat || !$no_telp) {
    echo "Data tidak lengkap!";
    exit();
}

$sql = "SELECT * FROM histori_diskon WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_produk]);
$produk = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit();
}

if ($jumlah > $produk['stok']) {
    echo "Jumlah melebihi stok tersedia.";
    exit();
}

$total_harga = $produk['harga_setelah_diskon'] * $jumlah;

$sql = "INSERT INTO transaksi (id_user, id_produk, jumlah, total_harga, alamat, no_telp, metode_pembayaran)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$berhasil = $stmt->execute([$id_user, $id_produk, $jumlah, $total_harga, $alamat, $no_telp, $metode_pembayaran]);

if ($berhasil) {
    $sisa_stok = $produk['stok'] - $jumlah;
    $sql_stok = "UPDATE histori_diskon SET stok = ? WHERE id = ?";
    $stmt_stok = $conn->prepare($sql_stok);
    $stmt_stok->execute([$sisa_stok, $id_produk]);

    echo "<script>alert('Transaksi berhasil! Terima kasih sudah berbelanja.');window.location.href='index.php';</script>";
} else {
    echo "Gagal menyimpan transaksi.";
}
?>
