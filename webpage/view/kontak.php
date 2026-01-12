<?php
// Mengimpor file konfigurasi database
include '../../dashboard_admin/model/config_db.php';

// Inisialisasi variabel untuk pesan sukses dan error
$success = '';
$error   = '';

// Memproses data formulir ketika metode yang digunakan adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil dan membersihkan data dari formulir
    $nama   = trim($_POST['nama'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $pesan  = trim($_POST['pesan'] ?? '');

    // Memeriksa apakah semua field wajib diisi
    if ($nama && $email && $pesan) {
        // Query SQL untuk memasukkan data kontak ke tabel 'kontak'
        // Menggunakan prepared statement dengan pg_query_params untuk keamanan
        $query = "INSERT INTO kontak (nama, email, pesan) VALUES ($1, $2, $3)";
        $result = pg_query_params($conn, $query, [$nama, $email, $pesan]);

        // Memeriksa hasil eksekusi query
        if ($result) {
            $success = "Pesan berhasil dikirim."; // Pesan sukses
        } else {
            $error = "Gagal menyimpan pesan."; // Pesan error jika query gagal
        }
    } else {
        $error = "Semua field wajib diisi."; // Pesan error jika ada field kosong
    }
}
?>

<?php 
// Menyertakan (include) file navbar/header
include __DIR__ . '/../template/navbar.php'; 
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - Lab Applied Informatics</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../template/style_dropdown.css?v=1.0">

    <style>
        /* Gaya CSS untuk bagian kontak */
        .contact-section {
            padding: 40px 0;
            background-color: #ffffff;
        }

        .contact-header {
            margin-bottom: 30px;
        }

        .contact-header i {
            color: #0b3b63;
            font-size: 36px;
            margin-right: 15px;
        }

        .contact-header h2 {
            display: inline-block;
            color: #0b3b63;
            font-weight: bold;
            font-size: 32px;
            text-transform: uppercase;
        }

        .contact-info-card {
            background-color: #0b3c75;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .contact-info-card i {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .contact-info-card p {
            font-size: 14px;
            margin-bottom: 0;
        }

        .contact-form label {
            font-weight: 600;
            color: #343a40;
            margin-bottom: 5px;
            display: block;
            text-transform: uppercase;
            font-size: 14px;
        }

        .contact-form .form-control {
            border-radius: 5px;
            padding: 10px 15px;
            margin-bottom: 15px;
        }

        .contact-form .btn-send {
            background-color: #0b3c75;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            width: 100%;
            transition: 0.3s ease;
        }

        .contact-form .btn-send:hover {
            background-color: #082d50;
        }
    </style>
</head>

<body>

    <div class="container contact-section">

        <div class="contact-header">
            <i class="fas fa-phone-alt"></i>
            <h2>Hubungi Kami</h2>
        </div>

        <div class="row">

            <div class="col-md-5">

                <?php 
                // Menampilkan pesan sukses atau error, jika ada
                if ($success): ?>
                    <div class="alert alert-success"><?= $success; ?></div>
                <?php elseif ($error): ?>
                    <div class="alert alert-danger"><?= $error; ?></div>
                <?php endif; ?>

                <form class="contact-form" method="POST">
                    <div class="mb-3">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap">
                    </div>

                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email Aktif">
                    </div>

                    <div class="mb-3">
                        <label for="pesan">Pesan</label>
                        <textarea class="form-control" id="pesan" name="pesan" rows="4" placeholder="Tulis pesan Anda"></textarea>
                    </div>

                    <button type="submit" class="btn btn-send">Kirim Pesan</button>
                </form>
            </div>

            <div class="col-md-7">
                <div class="row g-3">

                    <div class="col-md-4">
                        <div class="contact-info-card">
                            <i class="fas fa-map-marker-alt"></i>
                            <p>Lantai 2 Gedung Pascasarjana</p>
                            <p>Politeknik Negeri Malang</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="contact-info-card">
                            <i class="far fa-clock"></i>
                            <p>Jam Operasional</p>
                            <p>Senin – Jumat<br>08.00 – 16.00 WIB</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="contact-info-card">
                            <i class="far fa-envelope"></i>
                            <p>Email Laboratorium</p>
                            <p>email@labai.polinema.ac.id</p>
                        </div>
                    </div>

                    <div class="col-12">
                        <img src="../../dashboard_admin/assets/img/lab_activity.jpg"
                             alt="Kegiatan Lab Applied Informatics"
                             class="img-fluid rounded">
                    </div>

                </div>
            </div>

        </div>
    </div>

    <?php 
    // Menyertakan (include) file footer
    include __DIR__ . '/../template/footer.php'; 
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>