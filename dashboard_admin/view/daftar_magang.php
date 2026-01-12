<?php
include __DIR__ . '/../model/config_db.php';
include __DIR__ . '/../model/model_magang_admin.php';
include '../model/middleware.php';


// Ambil data pending dan diterima (LOGIKA TETAP)
$resultPending = getPendingMagang($conn);
$resultAccepted = getAcceptedMagang($conn);

$countPending = $resultPending ? pg_num_rows($resultPending) : 0;

// Mengambil semua data accepted ke dalam array untuk kemudahan penghitungan dan tampilan collapse
$allAccepted = [];
if ($resultAccepted) {
    while ($row = pg_fetch_assoc($resultAccepted)) {
        $allAccepted[] = $row;
    }
}
$countAccepted = count($allAccepted);
$limit = 3; // Batasan tampilan awal untuk tabel Accepted
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pendaftaran Magang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../template/style_sidebar.css">

    <style>
        .content {
            padding: 20px;
            /* Atur padding content dari 4px menjadi 20px */
        }

        /* Mengatur lebar kolom agar tabel lebih rapi */
        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 5%;
        }

        /* No */
        .table th:nth-child(8),
        .table td:nth-child(8) {
            width: 10%;
        }

        /* Status */
        .table th:nth-child(9),
        .table td:nth-child(9) {
            width: 10%;
        }

        /* Aksi */

        /* CSS untuk modal detail */
        .label-col {
            width: 180px;
            font-weight: 600;
        }

        /* Animasi baris tabel (untuk collapse) */
        .animated-row {
            opacity: 0;
            transform: translateY(15px);
            transition: all .35s ease;
        }

        .animated-row.show-row {
            opacity: 1;
            transform: translateY(0);
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
        <h4 class="fw-bold mb-4 text">Kelola Pendaftaran Magang</h4>

        <!-- notif -->
        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <?php if ($_GET['status'] === 'accepted'): ?>
                    <i class="bi bi-check-circle me-2"></i>
                    Pendaftar berhasil <strong>diterima</strong>.
                <?php elseif ($_GET['status'] === 'rejected'): ?>
                    <i class="bi bi-x-circle me-2"></i>
                    Pendaftar berhasil <strong>ditolak</strong>.
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>



        <h5 class="fw-semibold mb-3">Pengajuan Pendaftaran Magang (<?= $countPending ?>)</h5>
        <div class="card shadow-sm p-0 mb-5">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Prodi</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($countPending > 0): ?>
                            <?php pg_result_seek($resultPending, 0);
                            $no = 1;
                            while ($row = pg_fetch_assoc($resultPending)): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= htmlspecialchars($row['nim']) ?></td>
                                    <td><?= htmlspecialchars($row['prodi']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= htmlspecialchars($row['no_hp']) ?></td>

                                    <td>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    </td>

                                    <td class="text-nowrap">
                                        <button class="btn btn-info btn-sm action-detail text-white"
                                            data-id="<?= htmlspecialchars($row['id']) ?>"
                                            data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                            data-nim="<?= htmlspecialchars($row['nim']) ?>"
                                            data-prodi="<?= htmlspecialchars($row['prodi']) ?>"
                                            data-email="<?= htmlspecialchars($row['email']) ?>"
                                            data-no-hp="<?= htmlspecialchars($row['no_hp']) ?>"
                                            data-cv="<?= htmlspecialchars($row['cv']) ?>"
                                            data-ktm="<?= htmlspecialchars($row['ktm']) ?>"
                                            data-surat="<?= htmlspecialchars($row['surat_pengantar']) ?>"
                                            data-status="<?= htmlspecialchars($row['status']) ?>"
                                            data-persetujuan="<?= htmlspecialchars($row['persetujuan'] ?? '') ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailModal">
                                            <i class="bi bi-eye"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle me-2"></i> Tidak ada pendaftar Magang yang pending.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h5 class="fw-semibold mb-3">Pendaftaran Magang Diterima (<?= $countAccepted ?>)</h5>
        <div class="card shadow-sm p-0 mb-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Prodi</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($countAccepted > 0): ?>
                            <?php
                            $no = 1;
                            // Tampilkan $limit baris pertama secara langsung
                            for ($i = 0; $i < min($countAccepted, $limit); $i++):
                                $row = $allAccepted[$i];
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= htmlspecialchars($row['nim']) ?></td>
                                    <td><?= htmlspecialchars($row['prodi']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= htmlspecialchars($row['no_hp']) ?></td>
                                    <td><span class="badge bg-success"><?= htmlspecialchars($row['status'] ?? 'Disetujui') ?></span></td>

                                    <td class="text-nowrap">
                                        <button class="btn btn-danger btn-sm action-delete"
                                            data-id="<?= htmlspecialchars($row['id']) ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#actionModal">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endfor; ?>

                            <?php if ($countAccepted > $limit): ?>
                                <tr id="collapseRowControl">
                                    <td colspan="9" class="p-0 border-0">
                                        <div class="collapse" id="allAcceptedTable">
                                            <table class="table table-striped table-hover align-middle mb-0">
                                                <tbody>
                                                    <?php $no2 = $no;
                                                    for ($i = $limit; $i < $countAccepted; $i++):
                                                        $row = $allAccepted[$i];
                                                    ?>
                                                        <tr class="animated-row">
                                                            <td><?= $no2++ ?></td>
                                                            <td><?= htmlspecialchars($row['nama']) ?></td>
                                                            <td><?= htmlspecialchars($row['nim']) ?></td>
                                                            <td><?= htmlspecialchars($row['prodi']) ?></td>
                                                            <td><?= htmlspecialchars($row['email']) ?></td>
                                                            <td><?= htmlspecialchars($row['no_hp']) ?></td>
                                                            <td><span class="badge bg-success"><?= htmlspecialchars($row['status'] ?? 'Disetujui') ?></span></td>
                                                            <td class="text-nowrap">
                                                                <button class="btn btn-danger btn-sm action-delete"
                                                                    data-id="<?= htmlspecialchars($row['id']) ?>"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#actionModal">
                                                                    <i class="bi bi-trash"></i> Hapus
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endfor; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle me-2"></i> Tidak ada pendaftar Magang yang berstatus Diterima.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($countAccepted > $limit): ?>
                <div class="text-center py-3 border-top">
                    <button class="btn btn-outline-primary d-flex align-items-center gap-2 mx-auto"
                        type="button"
                        id="toggleAcceptedBtn"
                        data-bs-toggle="collapse"
                        data-bs-target="#allAcceptedTable"
                        aria-expanded="false"
                        aria-controls="allAcceptedTable">
                        <i class="bi bi-chevron-down" id="toggleIcon"></i> <span id="toggleText">Lihat Semua (<?= $countAccepted - $limit ?> lagi)</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <div class="modal fade" id="actionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 id="actionTitle" class="modal-title">Konfirmasi</h5>
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

    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-file-person-fill me-2"></i> Detail Pendaftar Magang</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <h6 class="fw-bold text-primary border-bottom pb-1">Data Diri Pendaftar</h6>
                    <table class="table table-sm table-borderless mb-4">
                        <tbody>
                            <tr>
                                <th class="label-col">Nama Lengkap</th>
                                <td id="d_nama"></td>
                            </tr>
                            <tr>
                                <th>NIM</th>
                                <td id="d_nim"></td>
                            </tr>
                            <tr>
                                <th>Program Studi</th>
                                <td id="d_prodi"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td id="d_email"></td>
                            </tr>
                            <tr>
                                <th>No HP</th>
                                <td id="d_nohp"></td>
                            </tr>
                            <tr>
                                <th>Status Pendaftaran</th>
                                <td id="d_status"></td>
                            </tr>
                        </tbody>
                    </table>

                    <h6 class="fw-bold text-primary border-bottom pb-1 mt-3">Dokumen Pendukung</h6>
                    <div class="row pt-2">
                        <div class="col-md-4 mb-2">
                            <i class="bi bi-file-earmark-person-fill me-1"></i> CV:
                            <a id="d_cv" href="#" target="_blank" class="btn btn-outline-primary btn-sm mt-1 w-100"><i class="bi bi-file-earmark-pdf"></i> Lihat File</a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <i class="bi bi-person-bounding-box me-1"></i> KTM:
                            <a id="d_ktm" href="#" target="_blank" class="btn btn-outline-primary btn-sm mt-1 w-100"><i class="bi bi-file-earmark-pdf"></i> Lihat File</a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <i class="bi bi-envelope-open-fill me-1"></i> Surat Pengantar:
                            <a id="d_surat" href="#" target="_blank" class="btn btn-outline-primary btn-sm mt-1 w-100"><i class="bi bi-file-earmark-pdf"></i> Lihat File</a>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-start">
                    <button class="btn btn-success" id="btnAccept"><i class="bi bi-check-circle"></i> Terima</button>
                    <button class="btn btn-danger" id="btnReject"><i class="bi bi-x-circle"></i> Tolak</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>

            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const actionModalEl = document.getElementById('actionModal');
        const actionTitle = document.getElementById("actionTitle");
        const actionMsg = document.getElementById("actionMessage");
        const actionBtn = document.getElementById("actionButton");
        const bsActionModal = new bootstrap.Modal(actionModalEl);
        const bsDetailModal = new bootstrap.Modal(document.getElementById('detailModal'));

        // SETUP MODAL KONFIRMASI (Reusable function)
        function setupActionModal(id, actionType, name) {
            let url = '';
            let btnClass = 'btn-danger';

            actionBtn.innerText = 'Ya, Lanjutkan';

            if (actionType === 'accept') {
                actionTitle.innerText = "Terima Pendaftar";
                actionMsg.innerHTML = `Setujui pendaftar **${name}**?`;
                btnClass = "btn-success";
                url = `../controller/magang_accept.php?id=${id}`;
            } else if (actionType === 'reject') {
                actionTitle.innerText = "Tolak Pendaftar";
                actionMsg.innerHTML = `Tolak pendaftar **${name}**?`;
                btnClass = "btn-danger";
                url = `../controller/magang_reject.php?id=${id}`;
            } else if (actionType === 'delete') {
                actionTitle.innerText = "Hapus Data";
                actionMsg.innerHTML = `Hapus data pendaftaran **${name}** permanen?`;
                btnClass = "btn-danger";
                url = `../controller/magang_delete.php?id=${id}`;
                actionBtn.innerText = 'Ya, Hapus';
            }

            actionBtn.className = `btn ${btnClass}`;
            actionBtn.onclick = () => window.location.href = url;
            bsActionModal.show();
        }


        // BUTTON ACTIONS

        // 1. DETAIL BUTTON (Pending Table)
        document.querySelectorAll(".action-detail").forEach(btn => {
            btn.onclick = () => {
                const id = btn.dataset.id;
                const nama = btn.dataset.nama;
                const status = btn.dataset.status;

                // Isi data modal detail
                document.getElementById("d_nama").innerText = nama;
                document.getElementById("d_nim").innerText = btn.dataset.nim;
                document.getElementById("d_prodi").innerText = btn.dataset.prodi;
                document.getElementById("d_email").innerText = btn.dataset.email;
                document.getElementById("d_nohp").innerText = btn.dataset.noHp;

                // Tampilkan status dengan badge
                const dStatus = document.getElementById("d_status");
                dStatus.innerHTML = status === 'Menunggu' ? '<span class="badge bg-warning text-dark">Pending</span>' :
                    status === 'Diterima' ? '<span class="badge bg-success">Diterima</span>' :
                    '<span class="badge bg-danger">Ditolak</span>';

                // FILE DOWNLOAD
                document.getElementById("d_cv").href = "../assets/magang/cv/" + btn.dataset.cv;
                document.getElementById("d_ktm").href = "../assets/magang/ktm/" + btn.dataset.ktm;
                document.getElementById("d_surat").href = "../assets/magang/surat/" + btn.dataset.surat;

                // Tampilkan/Sembunyikan tombol aksi Terima/Tolak
                const btnAccept = document.getElementById("btnAccept");
                const btnReject = document.getElementById("btnReject");

                if (status === 'Menunggu') {
                    btnAccept.style.display = 'inline-block';
                    btnReject.style.display = 'inline-block';

                    // SET BUTTON ACTION (Trigger konfirmasi modal)
                    btnAccept.onclick = () => {
                        bsDetailModal.hide();
                        setupActionModal(id, 'accept', nama);
                    };
                    btnReject.onclick = () => {
                        bsDetailModal.hide();
                        setupActionModal(id, 'reject', nama);
                    };
                } else {
                    btnAccept.style.display = 'none';
                    btnReject.style.display = 'none';
                }
            };
        });

        // 2. DELETE BUTTON (Accepted Table)
        document.querySelectorAll(".action-delete").forEach(el => {
            el.onclick = () => {
                const row = el.closest('tr');
                const nama = row.cells[1].innerText; // Ambil nama dari kolom kedua
                setupActionModal(el.dataset.id, 'delete', nama);
            };
        });

        // 3. Toggle Lihat Semua (Logika Visual)
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById('toggleAcceptedBtn');
            const collapseTable = document.getElementById('allAcceptedTable');
            const toggleIcon = document.getElementById('toggleIcon');
            const toggleTextSpan = document.getElementById('toggleText');
            const totalHiddenCount = <?= $countAccepted > $limit ? ($countAccepted - $limit) : 0 ?>;

            if (toggleBtn && collapseTable) {
                const rows = collapseTable.querySelectorAll('.animated-row');

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

                collapseTable.addEventListener('hide.bs.collapse', function() {
                    toggleTextSpan.innerText = `Lihat Semua (${totalHiddenCount} lagi)`;
                    toggleIcon.classList.remove('bi-chevron-up');
                    toggleIcon.classList.add('bi-chevron-down');

                    rows.forEach(row => {
                        row.classList.remove('show-row');
                    });
                });
            }
        });
    </script>

</body>

</html>