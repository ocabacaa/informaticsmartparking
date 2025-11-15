<?php
include 'phpqrcode/qrlib.php';
$conn = new mysqli("localhost", "root", "", "smartparking");

$msg = "";
$file = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $nim = strtolower(trim($_POST['nim']));


    if (!preg_match('/^d104\d{6,8}$/i', $nim)) {
        $msg = "
            <div class='alert alert-danger text-center'>
                NIM tidak valid! Hanya mahasiswa Informatika yang bisa mengakses.
            </div>
            <div class='text-center mt-3'>
                <a href='index.php' class='btn btn-dark'>⬅ Kembali ke Halaman Utama</a>
            </div>
        ";
        $file = "";
    }  
    else {
        $cek = $conn->query("SELECT * FROM mahasiswa WHERE nim='$nim'");
        if ($cek->num_rows > 0) {
            $msg = "
                <div class='alert alert-warning text-center'>
                    Kamu sudah terdaftar. Gunakan QR Codemu untuk parkir.
                </div>
            ";
            $row = $cek->fetch_assoc();
            $file = $row['qr_code'];
        } else {

            $data = $nim . '-' . $nama;
            $file = "qr/" . $nim . ".png";

            if (!file_exists('qr')) mkdir('qr', 0777, true);

            QRcode::png($data, $file, QR_ECLEVEL_L, 6, 4);

            $conn->query("INSERT INTO mahasiswa (nim, nama, qr_code) VALUES ('$nim', '$nama', '$file')");

            $msg = "
                <div class='alert alert-success text-center'>
                    QR Code berhasil dibuat!
                </div>
            ";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Parkir Mahasiswa Informatika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background: #eef1f6;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border-radius: 18px;
            padding: 25px;
        }
        h3 {
            font-weight: 700;
            color: #2c3e50;
        }
        .btn-primary, .btn-success, .btn-secondary, .btn-dark {
            padding: 10px 16px;
            border-radius: 10px;
            font-weight: 600;
            transition: 0.2s;
        }
        .btn-primary:hover,
        .btn-success:hover,
        .btn-secondary:hover,
        .btn-dark:hover {
            opacity: 0.85;
        }
        .qr-box img {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
    </style>
</head>

<body>
<div class="container mt-5">
    <div class="card shadow-lg mx-auto" style="max-width: 480px;">
        <h3 class="text-center mb-4">Registrasi Parkir Informatika</h3>

        <!-- Pesan -->
        <?= $msg ?>

        <!-- Jika ada QR -->
        <?php if (!empty($file)): ?>
            <div class="qr-box text-center">
                <img src="<?= $file ?>" alt="QR Code" class="img-thumbnail mb-3" style="width:210px;">
                <br>
                <a href="<?= $file ?>" download class="btn btn-primary mt-2">Download QR Code</a>

                <div class="mt-3">
                    <a href="index.php" class="btn btn-secondary">⬅ Kembali</a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <?php if (empty($msg) || empty($file)): ?>
            <form method="post" class="mt-3">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control form-control-lg" placeholder="Masukkan nama..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label">NIM</label>
                    <input type="text" name="nim" class="form-control form-control-lg" placeholder="Contoh: D104123456" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Daftar</button>
            </form>
        <?php endif; ?>

    </div>
</div>
</body>
</html>
