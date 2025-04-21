<?php
include "../lib/koneksi.php";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zenith Bags - Produk Diskon</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        padding: 20px;
    }

    .container {
        background: white;
        max-width: 90%;
        margin: auto;
        padding: 20px;
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

    .result {
        margin-top: 20px;
        padding: 10px;
        background: #e9ecef;
        border-radius: 5px;
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
        }
        .back-button:hover {
            background-color: #3e151c;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <center>
                <h1>Hitung Produk Diskon</h1>
                </center>
            </div>
        </div>
        <form method="POST" action="" enctype="multipart/form-data">
            <label>Nama Barang</label>
            <input type="text" name="nmbrng" required />

            <label>Masukkan Harga</label>
            <input type="number" name="bil1" required step="0.01" min="0" />
            
            <label>Masukkan Diskon (%)</label>
            <input type="number" name="bil2" required step="0.01" min="0" max="100" />

            <label>Stok</label>
            <input type="number" name="stok" required step="0.01" min="0" />

            <label>Upload Foto Produk</label>
            <input type="file" name="foto_produk" accept="image/*" required />

            <button type="submit" name="Submit">Hitung</button>
        </form>
        
        <div class="result">
            <?php 
                
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_barang = $_POST['nmbrng'];
    $harga = floatval($_POST['bil1']);
    $diskon = floatval($_POST['bil2']);
    $stok = floatval($_POST['stok']);
    $foto_produk = $_FILES['foto_produk'];

    if ($harga > 0 && $diskon >= 0 && $diskon <= 100) {
        $folder = "../uploads/";
        if (!file_exists($folder)) mkdir($folder, 0777, true);

        $file_name = time() . "_" . basename($foto_produk["name"]);
        $target_file = $folder . $file_name;
        
        if (move_uploaded_file($foto_produk["tmp_name"], $target_file)) {
            $harga_setelah_diskon = $harga - ($diskon / 100 * $harga);

            $stmt = $conn->prepare("INSERT INTO histori_diskon (nama_barang, harga, diskon, harga_setelah_diskon, stok, foto_produk) 
                                    VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nama_barang, $harga, $diskon, $harga_setelah_diskon, $stok, $file_name]);

            echo "<p style='color:green;'>Data berhasil disimpan!</p>";
        } else {
            echo "<p style='color:red;'>Gagal mengupload file!</p>";
        }
    } else {
        echo "<p style='color:red;'>Masukkan nilai yang valid!</p>";
    }
}
?>
        </div>
    </div>
    <div class="m-5 h-5">
        <a href="dashboard.php" class="back-button">Kembali</a>
    </div>
</body>

</html>