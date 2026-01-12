<?php
include '../model/config_db.php';
include '../model/model_membersMahasiswa.php';
include '../model/middleware.php';

// Pastikan fungsi ini mengembalikan resource atau array yang dapat di-loop
$members = getAllMembersMahasiswa($conn);
$total_members = pg_num_rows($members);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Members Mahasiswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="../template/style_sidebar.css">

    <style>
        .content {
            padding: 20px;
        }

        .search-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #888;
            pointer-events: none;
            /* Penting agar input bisa diklik */
        }

        #searchInput {
            padding-left: 32px;
            width: 250px;
            /* Atur lebar */
        }

        .member-photo {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }

        /* Styling untuk pesan jika data kosong */
        #empty-data {
            text-align: center;
            padding: 20px;
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

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0 text">Kelola Anggota Mahasiswa</h4>

            <div class="d-flex align-items-center gap-3">
                <div class="position-relative">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari Nama Mahasiswa">
                </div>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal" style="width: 200px;">
                    <i class="bi bi-plus-lg"></i> Tambah Mahasiswa
                </button>
            </div>
        </div>

        <div class="card shadow-sm p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Jurusan</th>
                            <th>Program Studi</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody">
                        <?php
                        $is_data_available = false;
                        if ($total_members > 0) {
                            $is_data_available = true;
                            while ($row = pg_fetch_assoc($members)) :
                                // Tentukan path foto, gunakan placeholder jika kosong
                                $foto_path = !empty($row['foto']) ? "../assets/img/members/mahasiswa/" . htmlspecialchars($row['foto']) : 'https://via.placeholder.com/60?text=No+Photo';

                                // Data attributes diisi dari data DB, termasuk ID untuk NIM/Nomor (Diasumsikan ID adalah NIM/Nomor)
                                $data_search = 'data-id="' . htmlspecialchars($row['id']) . '" ' .
                                    'data-nama="' . htmlspecialchars($row['nama']) . '" ' .
                                    'data-role="' . htmlspecialchars($row['role']) . '" ' .
                                    'data-jurusan="' . htmlspecialchars($row['jurusan']) . '" ' .
                                    'data-prodi="' . htmlspecialchars($row['program_studi']) . '"';
                        ?>
                                <tr class="search-row" <?= $data_search; ?>>
                                    <td class="small text-muted"><?= htmlspecialchars($row['id']); ?></td>

                                    <td>
                                        <img src="<?= $foto_path; ?>"
                                            alt="<?= htmlspecialchars($row['nama']); ?>"
                                            class="member-photo">
                                    </td>

                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['role']); ?></span></td>
                                    <td><?= htmlspecialchars($row['jurusan']); ?></td>
                                    <td><?= htmlspecialchars($row['program_studi']); ?></td>

                                    <td class="text-nowrap">
                                        <button
                                            class="btn btn-warning btn-sm btn-edit text-white"
                                            data-id="<?= htmlspecialchars($row['id']); ?>"
                                            data-nim="<?= htmlspecialchars($row['nim']); ?>"
                                            data-nama="<?= htmlspecialchars($row['nama']); ?>"
                                            data-role="<?= htmlspecialchars($row['role']); ?>"
                                            data-jurusan="<?= htmlspecialchars($row['jurusan']); ?>"
                                            data-prodi="<?= htmlspecialchars($row['program_studi']); ?>"
                                            data-foto="<?= htmlspecialchars($row['foto']); ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal">
                                            <i class="bi bi-pencil">Edit</i>
                                        </button>

                                        <a href="../controller/delete_membersMahasiswa.php?id=<?= urlencode($row['id']); ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus member <?= addslashes($row['nama']); ?>?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                            <?php endwhile;
                        }

                        if (!$is_data_available) : ?>
                            <tr id="no-data-available">
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-exclamation-circle me-2"></i> Belum ada data anggota mahasiswa yang ditambahkan.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL ADD -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="../controller/add_membersMahasiswa_action.php"
                method="POST"
                enctype="multipart/form-data"
                class="modal-content shadow">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Anggota</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" name="nim" id="edit-nim" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role (Misal: Anggota, Koordinator)</label>
                        <input type="text" name="role" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Program Studi</label>
                        <input type="text" name="program_studi" class="form-control" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Foto (Wajib)</label>
                        <input type="file" name="foto" class="form-control file-validate" accept="image/jpeg, image/png, image/jpg" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"> Tambah Anggota</button>
                </div>

            </form>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="../controller/update_membersMahasiswa.php"
                method="POST"
                enctype="multipart/form-data"
                class="modal-content shadow">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <input type="hidden" name="foto_lama" id="edit-foto-lama">

                    <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" name="nim" id="edit-nim" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" id="edit-nama" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" name="role" id="edit-role" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jurusan</label>
                        <input type="text" name="jurusan" id="edit-jurusan" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Program Studi</label>
                        <input type="text" name="program_studi" id="edit-prodi" class="form-control" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Foto Baru (Opsional)</label>
                        <input type="file" name="foto" class="form-control file-validate" accept="image/jpeg, image/png, image/jpg">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ==================== FILL EDIT MODAL ====================
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                // Mengisi data ke dalam form edit modal
                document.getElementById('edit-id').value = this.dataset.id;
                document.getElementById('edit-nim').value = this.dataset.nim;
                document.getElementById('edit-nama').value = this.dataset.nama;
                document.getElementById('edit-role').value = this.dataset.role;
                document.getElementById('edit-jurusan').value = this.dataset.jurusan;
                document.getElementById('edit-prodi').value = this.dataset.prodi;
                document.getElementById('edit-foto-lama').value = this.dataset.foto;

                // Kosongkan input file agar pengguna tidak terpaksa mengganti foto
                document.querySelector('#editModal input[name="foto"]').value = '';
            });
        });

        // ==================== FILE VALIDATION (Reusable for Add & Edit) ====================
        document.querySelectorAll(".file-validate").forEach(input => {
            input.addEventListener("change", function() {

                let file = this.files[0];
                if (!file) return;

                let allowed = ["image/jpeg", "image/png", "image/jpg"];

                if (!allowed.includes(file.type)) {
                    alert("File harus berupa JPG, JPEG, atau PNG!");
                    this.value = "";
                    return;
                }

                // Contoh batasan ukuran file 2MB
                if (file.size > 2 * 1024 * 1024) {
                    alert("Ukuran maksimal file 2MB!");
                    this.value = "";
                }
            });
        });

        // ==================== SEARCH FUNCTIONALITY ====================
        const searchInput = document.getElementById("searchInput");
        const tableBody = document.getElementById("tableBody");
        const rows = document.querySelectorAll(".search-row");
        const noResultsMessage = `
            <tr id="no-search-result">
                <td colspan="7" class="text-center text-muted py-4">
                    <i class="bi bi-search me-2"></i> Data anggota tidak ditemukan.
                </td>
            </tr>
        `;

        searchInput.addEventListener("input", function() {
            let keyword = this.value.toLowerCase();
            let found = false;

            // Hapus pesan "tidak ditemukan" yang ada
            const existingNoRow = document.getElementById("no-search-result");
            if (existingNoRow) {
                existingNoRow.remove();
            }

            // Hapus pesan "Belum ada data" jika ada
            const existingEmptyData = document.getElementById("no-data-available");
            if (existingEmptyData) {
                existingEmptyData.style.display = 'none';
            }

            rows.forEach(row => {
                // Ambil data dari data-attribute untuk pencarian yang lebih akurat
                let id = row.dataset.id ? row.dataset.id.toLowerCase() : '';
                let nama = row.dataset.nama ? row.dataset.nama.toLowerCase() : '';
                let role = row.dataset.role ? row.dataset.role.toLowerCase() : '';

                const isMatch = id.includes(keyword) || nama.includes(keyword) || role.includes(keyword);

                row.style.display = isMatch ? "" : "none";

                if (isMatch) {
                    found = true;
                }
            });

            // Tampilkan pesan "tidak ditemukan" jika tidak ada yang cocok dan input tidak kosong
            if (!found && keyword.length > 0) {
                tableBody.insertAdjacentHTML('beforeend', noResultsMessage);
            } else if (!found && keyword.length === 0 && existingEmptyData) {
                existingEmptyData.style.display = ''; // Tampilkan kembali pesan "Belum ada data" jika tidak ada hasil dan pencarian dikosongkan
            }
        });
    </script>

</body>

</html>