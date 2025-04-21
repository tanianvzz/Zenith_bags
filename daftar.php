<?php
session_start();
include "lib/koneksi.php"; 

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($email) || empty($password)) {
        $error = "Semua field wajib diisi!";
    } else {
        try {
            $sql_check = "SELECT * FROM users WHERE username = :username OR email = :email";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bindParam(':username', $username);
            $stmt_check->bindParam(':email', $email);
            $stmt_check->execute();
            
            if ($stmt_check->rowCount() > 0) {
                $error = "Username atau email sudah digunakan!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                $sql_insert = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bindParam(':username', $username);
                $stmt_insert->bindParam(':email', $email);
                $stmt_insert->bindParam(':password', $hashed_password);

                if ($stmt_insert->execute()) {
                    $success = "Pendaftaran berhasil! Silakan <a href='login.php'>login</a>.";
                } else {
                    $error = "Terjadi kesalahan saat mendaftar!";
                }
            }
        } catch (PDOException $e) {
            $error = "Kesalahan database: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | Zenith Bags</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            width: 100%;
            max-width: 400px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #561C24;
            border: none;
        }
        .btn-primary:hover {
            background-color: #3e151c;
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="card p-4">
        <div class="card-body">
            <h3 class="text-center mb-3">Daftar</h3>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Daftar</button>

                <p class="text-center mt-3">
                    Sudah punya akun? <a href="login.php">Login</a>
                </p>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
