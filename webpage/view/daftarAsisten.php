<!-- Koneksi Database -->
<?php
include '../../dashboard_admin/model/config_db.php';
include __DIR__ . '/../template/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Metadata dasar -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Judul halaman -->
    <title>Layanan Rekrut Asisten</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS dropdown navbar -->
    <link rel="stylesheet" href="../template/style_dropdown.css?v=1.0">

    <style>
        /* Background halaman */
        body {
            background-color: #f7f9fd;
        }

        /* Section form */
        .form-section {
            padding: 40px 0;
        }

        .form-control {
            max-height: fit-content;
        }

        /* Judul halaman */
        .title {
            font-size: 28px;
            font-weight: 700;
            color: #083b71;
            text-align: center;
            margin-bottom: 40px;
        }

        /* Form */
        input.form-control,
        textarea.form-control {
            height: 45px;
            border-radius: 5px;
        }

        textarea.form-control {
            height: 100px;
        }

        /* Tombol kirim */
        .btn-send {
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
            background: #17a2b8;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>

    <div class="container form-section">
        <h2 class="title">LAYANAN REKRUT ASISTEN</h2>
        <form action="../../dashboard_admin/controller/add_asisten_action.php"
            method="POST" enctype="multipart/form-data">

            <!-- Form Pendaftaran Asisten -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Nama -->
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input
                            type="text" name="nama" class="form-control" required
                            placeholder="Contoh: Sarah Wijaya">
                    </div>

                    <!-- Input NIM -->
                    <div class="mb-3">
                        <label for="nimPelamar" class="form-label">NIM</label>
                        <input
                            type="text" name="nim" id="nimPelamar" class="form-control" required
                            pattern="[0-9]{8,15}" title="NIM harus berupa angka (8 hingga 15 digit)"
                            placeholder="Masukkan NIM Anda">
                    </div>

                    <!-- Prodi -->
                    <div class="mb-3">
                        <label class="form-label">Prodi</label>
                        <input
                            type="text" name="prodi" class="form-control" required
                            placeholder="Contoh: Teknik Informatika">
                    </div>

                    <!-- Semester -->
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <input type="number" name="semester" class="form-control" min="1" max="12" value="1" required>
                    </div>

                    <!-- Angkatan Masuk -->
                    <div class="mb-3">
                        <label class="form-label">Angkatan Masuk</label>
                        <input type="number" name="angkatan_masuk" class="form-control" min="1900" max="<?= date('Y') ?>" value="<?= date('Y') ?>" required>
                    </div>

                    <!-- Deskripsi diri -->
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Diri Anda</label>
                        <textarea name="deskripsi_diri" class="form-control" rows="3" required
                            placeholder="Tuliskan ringkasan diri, minat, dan motivasi Anda menjadi Asisten."></textarea>
                    </div>

                    <!-- Riwayat -->
                    <div class="mb-3">
                        <label class="form-label">Riwayat Pengalaman</label>
                        <textarea name="riwayat_pengalaman" class="form-control" required
                            placeholder="Tuliskan pengalaman relevan (misal: pernah menjadi Asisten Lab, mengikuti pelatihan GIS, dll)."></textarea>
                    </div>

                    <!-- Peran -->
                    <div class="mb-3">
                        <label for="peran_asisten" class="form-label">Peran Asisten</label>
                        <select name="peran_asisten" id="peran_asisten" class="form-select" required>
                            <option value="" disabled selected class="">Pilih Peran</option>
                            <option value="asisten_pengolah">Asisten Pengolah & Dokumentasi Data Spasial</option>
                            <option value="operator">Operator & Dokumentasi QGIS</option>
                            <option value="operator">Asisten Implementasi & Dokumentasi Infrastruktur Laboratorium</option>
                        </select>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required
                            placeholder="Contoh: sarah.wijaya@students.ac.id">
                    </div>

                    <!-- No -->
                    <div class="mb-3">
                        <label class="form-label">No HP</label>
                        <input
                            type="text"
                            name="no_hp"
                            class="form-control"
                            required
                            placeholder="Contoh: 081234567890 (Pastikan hanya angka)">
                    </div>

                    <!-- Berkas -->
                    <div class="mb-3">
                        <label class="form-label">Berkas Pendukung (Opsional)</label>
                        <input
                            type="file" name="berkas" class="form-control" accept=".pdf">
                        <div class="form-text">Format yang diterima: PDF.</div>
                    </div>

                    <!-- CV -->
                    <div class="mb-3">
                        <label class="form-label">CV</label>
                        <input
                            type="file" name="cv" class="form-control" accept=".pdf" required>
                        <div class="form-text">Format yang diterima: PDF.</div>
                    </div>

                    <!-- Portofolio -->
                    <div class="mb-3">
                        <label class="form-label">Portofolio</label>
                        <input
                            type="file" name="portofolio" class="form-control" accept=".pdf" required>
                        <div class="form-text">Format yang diterima: PDF.</div>
                    </div>

                    <!-- Transkrip -->
                    <div class="mb-3">
                        <label class="form-label">Transkrip Nilai</label>
                        <input
                            type="file" name="transkrip_nilai" class="form-control" accept=".pdf" required>
                        <div class="form-text">Format yang diterima: PDF.</div>
                    </div>

                    <!-- Persetujuan -->
                    <div class="mb-3">
                        <label class="form-label">Persetujuan (Upload Tanda Tangan)</label>
                        <input
                            type="file" name="persetujuan" class="form-control" accept=".pdf" required>
                        <div class="form-text">Format yang diterima: PDF.</div>
                    </div>

                    <!-- Kirim -->
                    <div class="text-center">
                        <button class="btn btn-send" type="submit">Kirim</button>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <!-- MODAL SUCCESS -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Daftar Asisten Sedang Diproses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Pendaftaran asisten Anda telah berhasil dikirim.
                    Mohon menunggu konfirmasi lebih lanjut melalui email.
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-dismiss="modal">Oke</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include __DIR__ . '/../template/footer.php'; ?>

    <!-- JavaScript -->
    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            });
        </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>