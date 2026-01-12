<?php
include '../model/config_db.php';
include '../model/model_peminjaman_admin.php';
include '../model/middleware.php';

// Ambil data peminjaman
$resultPending  = getPendingPeminjaman($conn);
$resultAccepted = getAcceptedPeminjaman($conn);
$resultRejected = getRejectPeminjaman($conn);

//menghitung pendinh
$countPending = $resultPending ? pg_num_rows($resultPending) : 0;

// Mengambil semua data accepted ke dalam array untuk kemudahan penghitungan dan tampilan collapse
$riwayat = [];

// Diterima
if ($resultAccepted) {
    while ($row = pg_fetch_assoc($resultAccepted)) {
        $riwayat[] = $row;
    }
}

// Ditolak
if ($resultRejected) {
    while ($row = pg_fetch_assoc($resultRejected)) {
        $riwayat[] = $row;
    }
}

$countRiwayat = count($riwayat);
$limit = 3;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Peminjaman</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../template/style_sidebar.css">
    <style>
        /* CSS Tambahan untuk tampilan */
        .content {
            padding: 20px;
        }

        /* Styling badge status agar lebih informatif */
        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }

        /* Mengatur lebar kolom agar tabel lebih rapi */
        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 5%;
        }

        /* No */
        .table th:nth-child(9),
        .table td:nth-child(9) {
            width: 10%;
        }

        /* Status */
        .table th:nth-child(10),
        .table td:nth-child(10) {
            width: 10%;
        }

        /* Aksi */
        .keperluan-cell {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Animasi baris tabel (untuk collapse) */
        .animated-row {
            opacity: 0;
            transform: translateY(15px);
            transition: all 0.35s ease;
        }

        .animated-row.show-row {
            opacity: 1;
            transform: translateY(0);
        }

        #toggleAcceptedBtn {
            transition: all 0.2s ease;
        }

        h4.text {
            color: #083b71;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../template/sidebar.php'; ?>

    <?php include __DIR__ . '/../template/topbar.php'; ?>

    <div id="content" class="content">
        <h4 class="fw-bold mb-4 text">Kelola Peminjaman Ruang Lab</h4>

        <?php if (isset($_GET['status'])) : ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <?php
                if ($_GET['status'] == 'updated') echo "Status peminjaman berhasil diperbarui dan email telah dikirim ke peminjam.";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <h5 class="fw-semibold mb-3">Pengajuan Peminjaman Ruang (<?= $countPending ?>)</h5>
        <div class="card shadow-sm p-0 mb-5">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>NIM</th>
                            <th>Keperluan</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($countPending > 0) : ?>
                            <?php
                            pg_result_seek($resultPending, 0);
                            $no = 1;
                            while ($row = pg_fetch_assoc($resultPending)) :
                                $status = $row['status'] ?? 'pending';
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= htmlspecialchars($row['no_hp']) ?></td>
                                    <td><?= htmlspecialchars($row['nim']) ?></td>
                                    <td class="keperluan-cell" title="<?= htmlspecialchars($row['keperluan']) ?>">
                                        <?= htmlspecialchars($row['keperluan']) ?>
                                    </td>
                                    <td><?= date('d M Y', strtotime($row['tanggal_mulai'])) ?></td>
                                    <td><?= htmlspecialchars($row['waktu_mulai']) ?></td>
                                    <td>
                                        <?php if ($status == "pending") : ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php elseif ($status == "diterima") : ?>
                                            <span class="badge bg-success">Diterima</span>
                                        <?php elseif ($status == "ditolak") : ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-nowrap">
                                        <button class="btn btn-info btn-sm btn-detail text-white"
                                            data-id="<?= htmlspecialchars($row['id']) ?>"
                                            data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                            data-nim="<?= htmlspecialchars($row['nim']) ?>"
                                            data-email="<?= htmlspecialchars($row['email']) ?>"
                                            data-nohp="<?= htmlspecialchars($row['no_hp']) ?>"
                                            data-keperluan="<?= htmlspecialchars($row['keperluan']) ?>"
                                            data-tgl_mulai="<?= htmlspecialchars($row['tanggal_mulai']) ?>"
                                            data-tgl_selesai="<?= htmlspecialchars($row['tanggal_selesai']) ?>"
                                            data-waktu_mulai="<?= htmlspecialchars($row['waktu_mulai']) ?>"
                                            data-waktu_selesai="<?= htmlspecialchars($row['waktu_selesai']) ?>"
                                            data-status="<?= htmlspecialchars($row['status']) ?>"
                                            data-pdf="<?= htmlspecialchars($row['pdf'] ?? '') ?>"
                                            data-bs-toggle="modal" data-bs-target="#detailModal">
                                            <i class="bi bi-eye"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="bi bi-check-circle me-2"></i> Tidak ada permintaan peminjaman yang pending.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>


        <h5 class="fw-semibold mb-3">Riwayat Peminjaman Ruang (<?= $countRiwayat ?>)</h5>
        <div class="card shadow-sm p-0 mb-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>NIM</th>
                            <th>Keperluan</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($countRiwayat > 0): ?>
                            <?php $no = 1; ?>
                            <?php for ($i = 0; $i < $countRiwayat; $i++): ?>
                                <?php $row = $riwayat[$i]; ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['email'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['no_hp'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['nim'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['keperluan'] ?? '-') ?></td>
                                    <td><?= date('d M Y', strtotime($row['tanggal_mulai'])) ?></td>
                                    <td><?= htmlspecialchars($row['waktu_mulai'] ?? '-') ?></td>
                                    <td>
                                        <?php if ($row['status'] === 'diterima'): ?>
                                            <span class="badge bg-success">Diterima</span>
                                        <?php elseif ($row['status'] === 'ditolak'): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fst-italic text-muted">Riwayat</td>
                                </tr>
                            <?php endfor; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted">
                                    Belum ada riwayat peminjaman.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="detailModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i> Detail Peminjaman</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2">Informasi Peminjam</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Nama</th>
                                    <td id="detailNama"></td>
                                </tr>
                                <tr>
                                    <th>NIM</th>
                                    <td id="detailNim"></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td id="detailEmail"></td>
                                </tr>
                                <tr>
                                    <th>No HP</th>
                                    <td id="detailNoHp"></td>
                                </tr>
                            </tbody>
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2">Detail Permintaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Keperluan</th>
                                    <td id="detailKeperluan"></td>
                                </tr>
                                <tr>
                                    <th>Tgl Mulai</th>
                                    <td id="detailTglMulai"></td>
                                </tr>
                                <tr>
                                    <th>Tgl Selesai</th>
                                    <td id="detailTglSelesai"></td>
                                </tr>
                                <tr>
                                    <th>Waktu Mulai</th>
                                    <td id="detailWaktuMulai"></td>
                                </tr>
                                <tr>
                                    <th>Waktu Selesai</th>
                                    <td id="detailWaktuSelesai"></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td id="detailStatus"></td>
                                </tr>
                                <tr id="rowPdf" style="display:none">
                                    <th>File Pendukung</th>
                                    <td><a id="detailPdf" href="#" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-pdf"></i>Lihat File</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer d-flex justify-content-start">
                        <button id="btnAccept" class="btn btn-success me-2">Terima</button>
                        <button id="btnReject" class="btn btn-danger me-auto">Tolak</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="actionModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content shadow">
                    <div class="modal-header bg-danger text-white">
                        <h5 id="actionTitle" class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Konfirmasi Aksi</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="actionMessage">Apakah Anda yakin?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button id="actionButton" class="btn btn-primary">Ya, Lanjutkan</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Deklarasi variabel global untuk URL aksi
            let currentActionUrl = '';

            // =======================================================
            // Tombol Lihat Semua / Lebih Sedikit (Toggle)
            // =======================================================
            document.addEventListener("DOMContentLoaded", function() {
                const toggleBtn = document.getElementById('toggleRiwayatBtn');
                const collapseTable = document.getElementById('allRiwayatTable');
                const toggleIcon = document.getElementById('toggleIcon');
                const toggleTextSpan = document.getElementById('toggleText');
                const totalHiddenCount = <?= $countRiwayat > $limit ? ($countRiwayat - $limit) : 0 ?>;

                if (toggleBtn && collapseTable) {
                    const rows = collapseTable.querySelectorAll('.animated-row');

                    // Event saat Collapse dibuka
                    collapseTable.addEventListener('show.bs.collapse', function() {
                        toggleTextSpan.innerText = 'Lihat Lebih Sedikit';
                        toggleIcon.classList.remove('bi-chevron-down');
                        toggleIcon.classList.add('bi-chevron-up');

                        rows.forEach((row, index) => {
                            row.classList.remove('show-row');
                            setTimeout(() => {
                                row.classList.add('show-row');
                            }, index * 60);
                        });
                    });

                    // Event saat Collapse ditutup
                    collapseTable.addEventListener('hide.bs.collapse', function() {
                        toggleTextSpan.innerText = `Lihat Semua (${totalHiddenCount} lagi)`;
                        toggleIcon.classList.remove('bi-chevron-up');
                        toggleIcon.classList.add('bi-chevron-down');

                        rows.forEach(row => {
                            row.classList.remove('show-row');
                        });
                    });
                }

                // Modal Detail & Aksi (Terima/Tolak)
                const actionModal = new bootstrap.Modal(document.getElementById('actionModal'));
                const detailModal = document.getElementById('detailModal');
                const btnAccept = document.getElementById('btnAccept');
                const btnReject = document.getElementById('btnReject');

                document.querySelectorAll(".btn-detail").forEach(btn => {
                    btn.addEventListener("click", function() {
                        const data = this.dataset;
                        const peminjamanId = data.id;
                        const status = data.status;

                        // 1. Isi Data Modal Detail
                        document.getElementById('detailNama').innerText = data.nama;
                        document.getElementById('detailNim').innerText = data.nim;
                        document.getElementById('detailEmail').innerText = data.email;
                        document.getElementById('detailNoHp').innerText = data.nohp;
                        document.getElementById('detailKeperluan').innerText = data.keperluan;
                        document.getElementById('detailTglMulai').innerText = data.tgl_mulai;
                        document.getElementById('detailTglSelesai').innerText = data.tgl_selesai;
                        document.getElementById('detailWaktuMulai').innerText = data.waktu_mulai;
                        document.getElementById('detailWaktuSelesai').innerText = data.waktu_selesai;

                        const statusText = status === 'pending' ? '<span class="badge bg-warning text-dark">Pending</span>' :
                            status === 'DITERIMA' ? '<span class="badge bg-success">Diterima</span>' :
                            '<span class="badge bg-danger">Ditolak</span>';
                        document.getElementById('detailStatus').innerHTML = statusText;

                        // PDF File Check
                        const pdfRow = document.getElementById('rowPdf');
                        const pdfLink = document.getElementById('detailPdf');
                        if (data.pdf && data.pdf !== '') {
                            pdfLink.href = "../uploads/" + data.pdf;
                            pdfRow.style.display = '';
                        } else {
                            pdfRow.style.display = 'none';
                        }

                        // 2. Tampilkan/Sembunyikan Tombol Aksi (Terima/Tolak)
                        if (status === 'pending') {
                            btnAccept.style.display = 'inline-block';
                            btnReject.style.display = 'inline-block';

                            // Setup Tombol Terima
                            btnAccept.onclick = () => {
                                setupActionModal(
                                    peminjamanId,
                                    'accept',
                                    'Terima Peminjaman?',
                                    `Anda akan menerima peminjaman oleh ${data.nama}. Email notifikasi akan dikirim.`,
                                    'btn-success'
                                );
                                actionModal.show();
                                bootstrap.Modal.getInstance(detailModal).hide();
                            };

                            // Setup Tombol Tolak
                            btnReject.onclick = () => {
                                setupActionModal(
                                    peminjamanId,
                                    'reject',
                                    'Tolak Peminjaman?',
                                    `Anda akan menolak peminjaman oleh ${data.nama}. Email notifikasi akan dikirim.`,
                                    'btn-primary'
                                );
                                actionModal.show();
                                bootstrap.Modal.getInstance(detailModal).hide();
                            };
                        } else {
                            // Jika sudah diterima/ditolak, sembunyikan tombol aksi
                            btnAccept.style.display = 'none';
                            btnReject.style.display = 'none';
                        }
                    });
                });

                // Setup dan Submit Modal Konfirmasi
                function setupActionModal(id, actionType, title, message, btnClass) {
                    const actionButton = document.getElementById('actionButton');
                    const actionTitle = document.getElementById('actionTitle');

                    actionTitle.innerHTML = title;
                    document.getElementById('actionMessage').innerHTML = message;

                    // Tentukan URL aksi
                    if (actionType === 'accept') {
                        currentActionUrl = `../controller/peminjaman_accept.php?id=${id}`;
                    } else if (actionType === 'reject') {
                        currentActionUrl = `../controller/peminjaman_reject.php?id=${id}`;
                    }

                    // Update tombol submit
                    actionButton.className = `btn ${btnClass}`;
                    actionButton.innerText = actionType === 'delete' ? 'Ya, Hapus' : 'Ya, Kirim Status';
                }

                // Event listener untuk tombol submit di modal konfirmasi
                document.getElementById('actionButton').addEventListener('click', function() {
                    if (currentActionUrl) {
                        window.location.href = currentActionUrl;
                    }
                });

            });
        </script>

</body>

</html>