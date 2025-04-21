<?php
include "../lib/koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmtSelect = $conn->prepare("SELECT foto_produk FROM histori_diskon WHERE id = ?");
    $stmtSelect->execute([$id]);
    $data = $stmtSelect->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        $gambar = $data['foto_produk'];
        $path = "../uploads/" . $gambar;

        $stmtDelete = $conn->prepare("DELETE FROM histori_diskon WHERE id = ?");
        if ($stmtDelete->execute([$id])) {

            if (!empty($gambar) && file_exists($path)) {
                unlink($path);
            }

            echo "<div class='alert alert-success' role='alert' style='border-radius: 50px;'>
                    Data berhasil dihapus!
                  </div>";
            echo "<script>setTimeout(function(){ window.location.href = 'data_diskon.php'; }, 2000);</script>";
        } else {
            echo "<div class='alert alert-danger' role='alert' style='border-radius: 50px;'>
                    Gagal menghapus data!
                  </div>";
        }
    } else {
        echo "<div class='alert alert-warning' role='alert' style='border-radius: 50px;'>
                Data tidak ditemukan!
              </div>";
    }
} else {
    echo "<div class='alert alert-warning' role='alert' style='border-radius: 50px;'>
            ID tidak ditemukan!
          </div>";
}
?>
