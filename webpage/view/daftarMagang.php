<!-- Koneksi Database -->
<?php
// Menghubungkan halaman dengan database
include '../../dashboard_admin/model/config_db.php';

// Memanggil navbar utama website
include __DIR__ . '/../template/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Metadata dasar -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Judul halaman -->
    <title>Daftar Magang - Lab Applied Informatics</title>

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

        /* Input dan textarea */
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

        /* Efek hover tombol */
        .btn-send:hover {
            background: #17a2b8;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>

    <!-- Container utama form -->
    <div class="container form-section">
        <h2 class="title">LAYANAN DAFTAR MAGANG</h2>

        <!-- Form pendaftaran magang -->
        <form action="../../dashboard_admin/controller/add_magang_action.php"
            method="POST" enctype="multipart/form-data">

            <!-- Grid Bootstrap -->
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <!-- Input Nama Lengkap -->
                    <div class="mb-3">
                        <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                        <input
                            type="text"
                            name="nama"
                            id="namaLengkap"
                            class="form-control"
                            required
                            placeholder="Masukkan Nama Anda">
                    </div>

                    <!-- Input NIM -->
                    <div class="mb-3">
                        <label for="nimPelamar" class="form-label">NIM</label>
                        <input
                            type="text"
                            name="nim"
                            id="nimPelamar"
                            class="form-control" required
                            pattern="[0-9]{8,15}"
                            title="NIM harus berupa angka (8 hingga 15 digit)"
                            placeholder="Masukkan NIM Anda">
                    </div>

                    <!-- Input Program Studi -->
                    <div class="mb-3">
                        <label for="prodiInstitusi" class="form-label">Program Studi</label>
                        <input
                            type="text"
                            name="prodi"
                            id="prodiInstitusi"
                            class="form-control"
                            required
                            placeholder="Contoh: Teknik Informatika">
                    </div>

                    <!-- Input Email -->
                    <div class="mb-3">
                        <label for="emailPelamar" class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="emailPelamar"
                            class="form-control"
                            required
                            placeholder="Contoh: nama.anda@domain.com">
                    </div>

                    <!-- Input Nomor Handphone -->
                    <div class="mb-4">
                        <label for="noHp" class="form-label">Nomor Handphone</label>
                        <input
                            type="tel"
                            name="no_hp"
                            id="noHp"
                            class="form-control"
                            required
                            pattern="[0-9]{10,15}"
                            title="Nomor HP harus berupa angka (10-15 digit)"
                            placeholder="Contoh: 081234567890">
                    </div>

                    <!-- Upload CV -->
                    <div class="mb-3">
                        <label for="cvFile" class="form-label">CV <span class="text-danger">*</span></label>
                        <input
                            type="file"
                            name="cv"
                            id="cvFile"
                            class="form-control"
                            accept=".pdf"
                            required>
                        <div class="form-text">Format yang diterima: PDF.</div>
                    </div>

                    <!-- Upload KTM -->
                    <div class="mb-4">
                        <label for="ktm" class="form-label">Kartu Tanda Mahasiswa (KTM) <span class="text-danger">*</span></label>
                        <input
                            type="file"
                            name="ktm"
                            id="ktm"
                            class="form-control"
                            accept=".pdf"
                            required>
                        <div class="form-text">Format yang diterima: PDF.</div>
                    </div>

                    <!-- Upload Surat Pengantar (Opsional) -->
                    <div class="mb-3">
                        <label for="suratPengantar" class="form-label">Surat Pengantar (Opsional)</label>
                        <input
                            type="file"
                            name="surat_pengantar"
                            id="suratPengantar"
                            class="form-control"
                            accept=".pdf">
                        <div class="form-text">Format yang diterima: PDF.</div>
                    </div>

                    <!-- Tombol kirim -->
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

                <!-- Header modal -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Pendaftaran Magang Sedang Diproses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Isi modal -->
                <div class="modal-body">
                    Pendaftaran magang Anda telah berhasil dikirim.
                    Mohon menunggu konfirmasi lebih lanjut melalui email.
                </div>

                <!-- Footer modal -->
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-dismiss="modal">Oke</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer website -->
    <?php include __DIR__ . '/../template/footer.php'; ?>

    <!-- Script untuk menampilkan modal jika status sukses -->
    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            });
        </script>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
