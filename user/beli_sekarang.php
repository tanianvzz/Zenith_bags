<?php
session_start();
include "../lib/koneksi.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID tidak valid.";
    exit;
}

$id = $_GET['id'];

$sql = "SELECT id, nama_barang, harga_setelah_diskon AS harga, foto_produk FROM histori_diskon WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$produk = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit;
}
$user_id = $_SESSION['id_user'];
if (!isset($_SESSION['keranjang'][$user_id])) {
    $_SESSION['keranjang'][$user_id] = [];
}

$_SESSION['keranjang'][$user_id][$id] = [
    'nama' => $produk['nama_barang'],
    'harga' => $produk['harga'],
    'foto' => $produk['foto_produk'],
    'jumlah' => 1
];


header("Location: keranjang.php");
exit;
