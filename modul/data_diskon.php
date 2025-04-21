<?php
include "../lib/koneksi.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Diskon - Zenith Bags</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding: 40px 20px;
        }

        .container-custom {
            max-width: 1100px;
            margin: auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #561C24;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #561C24;
            color: white;
            text-align: center;
        }

        td, th {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        img {
            max-width: 80px;
            border-radius: 8px;
        }

        .btn-hapus {
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
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
        }

        .back-button:hover {
            background-color: #3e151c;
        }

        @media (max-width: 768px) {
            .container-custom {
                padding: 20px;
            }

            table, thead, tbody, th, td, tr {
                display: block;
            }

            th {
                position: sticky;
                top: 0;
                z-index: 2;
            }

            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                font-weight: bold;
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <h2>Riwayat Data Diskon</h2>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Harga Awal</th>
                        <th>Diskon</th>
                        <th>Harga Diskon</th>
                        <th>Stok</th>
                        <th>Foto Produk</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM histori_diskon ORDER BY tanggal DESC LIMIT 5";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($result as $row) {
                        echo "<tr>
                                <td data-label='Nama Barang'>" . htmlspecialchars($row["nama_barang"]) . "</td>
                                <td data-label='Harga Awal'>Rp " . number_format($row["harga"], 2, ",", ".") . "</td>
                                <td data-label='Diskon'>{$row["diskon"]}%</td>
                                <td data-label='Harga Diskon'>Rp " . number_format($row["harga_setelah_diskon"], 2, ",", ".") . "</td>
                                <td data-label='Stok'>{$row["stok"]}</td>
                                <td data-label='Foto'><img src='../uploads/" . htmlspecialchars($row["foto_produk"]) . "' alt='Foto Produk'></td>
                                <td data-label='Tanggal'>{$row["tanggal"]}</td>
                                <td data-label='Aksi'><a href='hapus.php?id={$row["id"]}' class='btn-hapus'>Hapus</a></td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="dashboard.php" class="back-button">← Kembali ke Dashboard</a>
    </div>
</body>
</html>
