<?php
// Mengimpor file konfigurasi database dan template navbar
include '../../dashboard_admin/model/config_db.php';
include __DIR__ . '/../template/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Peminjaman Ruangan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../template/style_dropdown.css?v=1.0">


    <style>
        /* Gaya CSS kustom untuk halaman */
        body {
            background-color: #f7f9fd;
        }

        .form-section {
            /* Padding atas dan bawah untuk area formulir */
            padding: 40px 0;
        }

        .form-control {
            /* Mengatur tinggi maksimum kontrol formulir agar sesuai dengan konten */
            max-height: fit-content;
        }

        .title {
            /* Gaya untuk judul utama formulir */
            font-size: 28px;
            font-weight: 700;
            color: #083b71;
            text-align: center;
            margin-bottom: 40px;
        }

        .btn-send {
            /* Gaya untuk tombol 'Kirim' */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 120px;
            height: 45px;
            background: white;
            color: #666;
            text-decoration: none;
            border-radius: 8px;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            flex-shrink: 0;
            margin-top: 20px;
            border: 2px solid #083b71;
        }

        .btn-send:hover {
            /* Efek hover pada tombol 'Kirim' */
            background: #17a2b8;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        small.text-muted {
            /* Gaya untuk teks kecil/petunjuk */
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container form-section">
        <h2 class="title">LAYANAN PEMINJAMAN RUANGAN</h2>

        <form action="../../dashboard_admin/controller/add_peminjaman.php" method="POST">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="mb-3">
                        <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                        <input
                            type="text" name="nama" id="namaLengkap" class="form-control" required
                            placeholder="Contoh: Budi Santoso">
                    </div>

                    <div class="mb-3">
                        <label for="nimMahasiswa" class="form-label">NIM (Nomor Induk Mahasiswa)</label>
                        <input
                            type="tel" name="nim" id="nimMahasiswa" class="form-control" required
                            pattern="[0-7]{8,15}" placeholder="Contoh: 195150201111001">
                        <div class="form-text">Hanya angka, minimal 8 digit</div>
                    </div>

                    <div class="mb-3">
                        <label for="emailPemohon" class="form-label">Email</label>
                        <input
                            type="email" name="email" id="emailPemohon" class="form-control" required
                            placeholder="Contoh: budi.santoso@students.ac.id">
                    </div>

                    <div class="mb-4">
                        <label for="noHp" class="form-label">Nomor Handphone</label>
                        <input
                            type="tel" name="nohp" id="noHp" class="form-control" required
                            pattern="[0-9]{10,15}"
                            placeholder="Contoh: 081234567890">
                        <div class="form-text">Nomor HP harus berupa angka (10-15 digit)</div>
                    </div>

                    <div class="mb-3">
                        <label for="keperluan" class="form-label">Keperluan</label>
                        <textarea
                            name="keperluan"
                            id="keperluan"
                            class="form-control"
                            rows="3"
                            required
                            placeholder="Jelaskan kebutuhan Anda secara rinci (misal: Praktikum Modul X, Penelitian Tugas Akhir, Rapat Kelompok Studi)"></textarea>
                    </div>

                    <div class="row mb-3">
                        <label class="form-label">Waktu Mulai</label>
                        <div class="col-6">
                            <input type="date" name="tgl_mulai" class="form-control" required
                                min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-6">
                            <input type="time" name="jam_mulai" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="form-label">Waktu Selesai</label>
                        <div class="col-6">
                            <input type="date" name="tgl_selesai" class="form-control" required
                                min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-6">
                            <input type="time" name="jam_selesai" class="form-control" required>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-send" type="submit">Kirim</button>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Peminjaman Sedang Diproses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Peminjaman ruangan Anda telah berhasil dikirim.
                    Mohon menunggu konfirmasi lebih lanjut melalui email.
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-dismiss="modal">Oke</button>
                </div>

            </div>
        </div>
    </div>

    <!-- footer -->
    <?php include __DIR__ . '/../template/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <script>
            // Jalankan script setelah dokumen dimuat sepenuhnya
            document.addEventListener("DOMContentLoaded", function() {
                // Inisialisasi modal sukses menggunakan Bootstrap JS
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                // Tampilkan modal
                successModal.show();
            });
        </script>
    <?php endif; ?>

</body>

</html>