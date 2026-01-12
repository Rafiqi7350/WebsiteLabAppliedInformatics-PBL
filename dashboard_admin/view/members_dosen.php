<?php
include '../model/config_db.php';
include '../model/model_membersDosen.php';
include '../model/middleware.php';

$members_result = getAllMembersDosen($conn); // Simpan hasil query asli untuk tabel Dosen

// Query untuk Sosial Media (Diambil langsung di PHP untuk keperluan tabel kedua)
$query_social = "SELECT id, nama, scholar_link, sinta_link, scopus_link, orcid_link 
                 FROM member_dosen 
                 WHERE scholar_link IS NOT NULL 
                 OR sinta_link IS NOT NULL 
                 OR scopus_link IS NOT NULL 
                 OR orcid_link IS NOT NULL
                 ORDER BY nama ASC";
$result_social = pg_query($conn, $query_social);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members Dosen</title>

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
        }

        #searchInput {
            padding-left: 32px;
            width: 250px;
        }

        .social-link {
            display: inline-block;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .member-photo {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }

        /* Styling untuk sel yang berisi konten panjang */
        .scrollable-cell {
            max-height: 80px;
            overflow-y: auto;
            word-break: break-word;
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
            <h4 class="fw-bold mb-0 text"> Kelola Anggota Dosen</h4>

            <div class="d-flex align-items-center gap-3">
                <div class="position-relative">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari Nama Dosen">
                </div>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal" style="width: 180px;">
                    <i class="bi bi-plus-lg"></i> Tambah Dosen
                </button>
            </div>
        </div>

        <div class="card shadow-sm mb-5">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th style="width: 80px;">Foto</th>
                                <th style="width: 15%;">Nama</th>
                                <th style="width: 10%;">Role</th>
                                <th style="width: 15%;">Expertise</th>
                                <th style="width: 30%;">Deskripsi</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="dosenTableBody">
                            <?php
                            $is_dosen_available = false;
                            while ($row = pg_fetch_assoc($members_result)) :
                                $is_dosen_available = true;
                                $foto_path = (!empty($row['foto']) && file_exists("../assets/img/members/dosen/" . $row['foto'])) ?
                                    "../assets/img/members/dosen/" . htmlspecialchars($row['foto']) : 'https://via.placeholder.com/60?text=No+Photo';
                            ?>
                                <tr class="dosen-row"
                                    data-id="<?= htmlspecialchars($row['id']); ?>"
                                    data-nama="<?= htmlspecialchars($row['nama']); ?>"
                                    data-role="<?= htmlspecialchars($row['role']); ?>">

                                    <td class="small text-muted"><?= htmlspecialchars($row['id']); ?></td>

                                    <td>
                                        <img src="<?= $foto_path; ?>"
                                            alt="<?= htmlspecialchars($row['nama']); ?>"
                                            class="member-photo">
                                    </td>

                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['role']); ?></span></td>
                                    <td><div class="scrollable-cell small text-muted"><?= htmlspecialchars($row['expertise']); ?></div></td>
                                    <td><div class="scrollable-cell small text-muted"><?= htmlspecialchars($row['deskripsi']); ?></div></td>

                                    <td class="text-nowrap">
                                        <button
                                            class="btn btn-warning btn-sm btn-edit"
                                            data-id="<?= htmlspecialchars($row['id']); ?>"
                                            data-nama="<?= htmlspecialchars($row['nama']); ?>"
                                            data-role="<?= htmlspecialchars($row['role']); ?>"
                                            data-expertise="<?= htmlspecialchars($row['expertise']); ?>"
                                            data-deskripsi="<?= htmlspecialchars($row['deskripsi']); ?>"
                                            data-foto="<?= htmlspecialchars($row['foto']); ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal">
                                            <i class="bi bi-pencil">
                                                Edit
                                            </i>
                                        </button>

                                        <a href="../controller/delete_membersDosen.php?id=<?= urlencode($row['id']); ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus dosen <?= addslashes($row['nama']); ?>?');">
                                            <i class="bi bi-trash">Hapus</i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if (!$is_dosen_available) : ?>
                                <tr id="no-dosen-available">
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-person-x" style="font-size: 2rem;"></i>
                                        <p class="mt-2 mb-0">Belum ada data anggota Dosen yang ditambahkan.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0 text"> Kelola Sosial Media Dosen</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSocialModal" style="width: 200px;">
                <i class="bi bi-plus-lg"></i> Tambah Sosial Media
            </button>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 15%;">Nama Dosen</th>
                                <th style="width: 15%;">Google Scholar</th>
                                <th style="width: 15%;">SINTA</th>
                                <th style="width: 15%;">Scopus</th>
                                <th style="width: 15%;">ORCID</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $is_social_available = false;
                            $no = 1;

                            // Reset pointer (jika result_social digunakan sebelumnya, walaupun di atas sudah di query ulang)
                            if (pg_num_rows($result_social) > 0) {
                                pg_result_seek($result_social, 0);
                            }
                            
                            while ($social = pg_fetch_assoc($result_social)) :
                                $is_social_available = true;
                                $display_name = htmlspecialchars($social['nama']);
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $display_name; ?></td>
                                    <td>
                                        <?php if (!empty($social['scholar_link'])) : ?>
                                            <a href="<?= htmlspecialchars($social['scholar_link']); ?>"
                                                target="_blank"
                                                class="social-link text-primary small" title="<?= htmlspecialchars($social['scholar_link']); ?>">
                                                <i class="bi bi-link-45deg"></i> Link
                                            </a>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($social['sinta_link'])) : ?>
                                            <a href="<?= htmlspecialchars($social['sinta_link']); ?>"
                                                target="_blank"
                                                class="social-link text-primary small" title="<?= htmlspecialchars($social['sinta_link']); ?>">
                                                <i class="bi bi-link-45deg"></i> Link
                                            </a>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($social['scopus_link'])) : ?>
                                            <a href="<?= htmlspecialchars($social['scopus_link']); ?>"
                                                target="_blank"
                                                class="social-link text-primary small" title="<?= htmlspecialchars($social['scopus_link']); ?>">
                                                <i class="bi bi-link-45deg"></i> Link
                                            </a>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($social['orcid_link'])) : ?>
                                            <a href="<?= htmlspecialchars($social['orcid_link']); ?>"
                                                target="_blank"
                                                class="social-link text-primary small" title="<?= htmlspecialchars($social['orcid_link']); ?>">
                                                <i class="bi bi-link-45deg"></i> Link
                                            </a>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-nowrap">
                                        <button
                                            class="btn btn-warning btn-sm btn-edit-social"
                                            data-id="<?= htmlspecialchars($social['id']); ?>"
                                            data-nama="<?= $display_name; ?>"
                                            data-scholar="<?= htmlspecialchars($social['scholar_link']); ?>"
                                            data-sinta="<?= htmlspecialchars($social['sinta_link']); ?>"
                                            data-scopus="<?= htmlspecialchars($social['scopus_link']); ?>"
                                            data-orcid="<?= htmlspecialchars($social['orcid_link']); ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editSocialModal">
                                            <i class="bi bi-pencil">Edit</i>
                                        </button>

                                        <button
                                            class="btn btn-danger btn-sm"
                                            onclick="deleteSocial(<?= $social['id']; ?>, '<?= addslashes($display_name); ?>')">
                                            <i class="bi bi-trash">Hapus</i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>

                            <?php if (!$is_social_available) : ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-globe" style="font-size: 2rem;"></i>
                                        <p class="mt-2 mb-0">Belum ada data sosial media Dosen yang terdaftar.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="../controller/add_membersDosen_action.php"
                method="POST"
                enctype="multipart/form-data"
                class="modal-content shadow">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="admin_id" value="1">

                    <div class="mb-3">
                        <label class="form-label ">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label ">Role</label>
                        <input type="text" name="role" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label ">Expertise</label>
                        <input type="text" name="expertise" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label ">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label ">Foto</label>
                        <input type="file" name="foto" class="form-control file-validate" accept="image/jpeg, image/png, image/jpg" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="../controller/update_membersDosen.php"
                method="POST"
                enctype="multipart/form-data"
                class="modal-content shadow">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id" id="edit-id">
                    <input type="hidden" name="foto_lama" id="edit-foto-lama">

                    <div class="mb-3 text-center">
                        <img id="edit-preview" src="" class="rounded mb-2 border border-secondary"
                            width="120" height="120" style="object-fit: cover; margin:auto;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label ">Nama</label>
                        <input type="text" name="nama" id="edit-nama" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label ">Role</label>
                        <input type="text" name="role" id="edit-role" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Expertise</label>
                        <input type="text" name="expertise" id="edit-expertise" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label ">Deskripsi</label>
                        <textarea name="deskripsi" id="edit-deskripsi" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Foto Baru (opsional)</label>
                        <input type="file" name="foto" id="edit-foto" class="form-control file-validate" accept="image/jpeg, image/png, image/jpg">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div class="modal fade" id="addSocialModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="../controller/sosial_media_action.php" method="POST" class="modal-content shadow">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Sosial Media Link</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label ">Pilih Dosen</label>
                        <select name="dosen_id" class="form-select" required>
                            <option value="">-- Pilih Dosen --</option>
                            <?php
                            // Query semua dosen lagi untuk dropdown
                            $query_dosen = "SELECT id, nama FROM member_dosen ORDER BY nama ASC";
                            $result_dosen = pg_query($conn, $query_dosen);
                            while ($d = pg_fetch_assoc($result_dosen)) :
                            ?>
                                <option value="<?= htmlspecialchars($d['id']); ?>"><?= htmlspecialchars($d['nama']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label ">Google Scholar</label>
                        <input type="url" name="scholar_link" class="form-control" placeholder="https://scholar.google.com/...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label ">SINTA</label>
                        <input type="url" name="sinta_link" class="form-control" placeholder="https://sinta.kemdikbud.go.id/...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label ">Scopus</label>
                        <input type="url" name="scopus_link" class="form-control" placeholder="https://www.scopus.com/...">
                    </div>

                    <div class="mb-0">
                        <label class="form-label ">ORCID</label>
                        <input type="url" name="orcid_link" class="form-control" placeholder="https://orcid.org/...">
                    </div>

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="save_social" value="1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add Link
                    </button>
                </div>

            </form>
        </div>
    </div>


    <div class="modal fade" id="editSocialModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="../controller/sosial_media_action.php" method="POST" class="modal-content shadow">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Sosial Media</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="dosen_id" id="edit-social-id">

                    <div class="mb-3">
                        <label class="form-label ">Nama Dosen</label>
                        <input type="text" id="edit-social-nama" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Google Scholar</label>
                        <input type="url" name="scholar_link" id="edit-social-scholar" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">SINTA</label>
                        <input type="url" name="sinta_link" id="edit-social-sinta" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Scopus</label>
                        <input type="url" name="scopus_link" id="edit-social-scopus" class="form-control">
                    </div>

                    <div class="mb-0">
                        <label class="form-label">ORCID</label>
                        <input type="url" name="orcid_link" id="edit-social-orcid" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="save_social" value="1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>

    <form id="deleteSocialForm" method="POST" action="../controller/sosial_media_action.php" style="display:none;">
        <input type="hidden" name="dosen_id" id="delete-social-id-hidden">
        <input type="hidden" name="delete_social" value="1">
    </form>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ==================== SET DATA EDIT MODAL DOSEN ====================
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-id').value = this.dataset.id;
                document.getElementById('edit-nama').value = this.dataset.nama;
                document.getElementById('edit-role').value = this.dataset.role;
                document.getElementById('edit-expertise').value = this.dataset.expertise;
                document.getElementById('edit-deskripsi').value = this.dataset.deskripsi;
                document.getElementById('edit-foto-lama').value = this.dataset.foto;
                document.getElementById('edit-foto').value = ''; // Reset input file

                let foto = this.dataset.foto;
                let imgPreview = document.getElementById('edit-preview');

                if (foto && foto !== "") {
                    imgPreview.src = "../assets/img/members/dosen/" + foto;
                } else {
                    imgPreview.src = "https://via.placeholder.com/120?text=No+Photo";
                }
            });
        });

        // ==================== LIVE PREVIEW FOTO BARU ====================
        document.getElementById('edit-foto').addEventListener('change', function() {
            const imgPreview = document.getElementById('edit-preview');
            if (this.files && this.files[0]) {
                imgPreview.src = URL.createObjectURL(this.files[0]);
            }
        });

        // ==================== FILE VALIDATION (for Dosen Add/Edit) ====================
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
                // Optional: Check file size (e.g., max 2MB)
                if (file.size > 2 * 1024 * 1024) { 
                    alert("Ukuran maksimal file 2MB!");
                    this.value = "";
                }
            });
        });

        // ==================== SET DATA EDIT MODAL SOCIAL MEDIA ====================
        document.querySelectorAll('.btn-edit-social').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-social-id').value = this.dataset.id;
                document.getElementById('edit-social-nama').value = this.dataset.nama;
                document.getElementById('edit-social-scholar').value = this.dataset.scholar || '';
                document.getElementById('edit-social-sinta').value = this.dataset.sinta || '';
                document.getElementById('edit-social-scopus').value = this.dataset.scopus || '';
                document.getElementById('edit-social-orcid').value = this.dataset.orcid || '';
            });
        });

        // ==================== DELETE SOCIAL MEDIA ====================
        function deleteSocial(id, nama) {
            if (confirm(`Yakin hapus semua link sosial media untuk Dosen: ${nama}?`)) {
                document.getElementById('delete-social-id-hidden').value = id;
                document.getElementById('deleteSocialForm').submit();
            }
        }

        // ==================== SEARCH FUNCTIONALITY (DOSEN TABLE) ====================
        const searchInput = document.getElementById("searchInput");
        const dosenTableBody = document.getElementById("dosenTableBody");
        const dosenRows = document.querySelectorAll(".dosen-row");
        const noResultsMessage = `
            <tr id="no-search-result-dosen">
                <td colspan="7" class="text-center text-muted py-4">
                    <i class="bi bi-search me-2"></i> Dosen tidak ditemukan.
                </td>
            </tr>
        `;
        const noDosenAvailableRow = document.getElementById("no-dosen-available");


        searchInput.addEventListener("input", function() {
            let keyword = this.value.toLowerCase();
            let found = false;

            // Hapus pesan "tidak ditemukan" yang ada
            const existingNoRow = document.getElementById("no-search-result-dosen");
            if (existingNoRow) {
                existingNoRow.remove();
            }
            
            // Sembunyikan pesan "Belum ada data"
            if (noDosenAvailableRow) {
                noDosenAvailableRow.style.display = 'none';
            }

            dosenRows.forEach(row => {
                let nama = row.dataset.nama ? row.dataset.nama.toLowerCase() : '';
                let role = row.dataset.role ? row.dataset.role.toLowerCase() : '';
                let id = row.dataset.id ? row.dataset.id.toLowerCase() : '';

                const isMatch = id.includes(keyword) || nama.includes(keyword) || role.includes(keyword);
                
                row.style.display = isMatch ? "" : "none";

                if (isMatch) {
                    found = true;
                }
            });

            // Tampilkan pesan "tidak ditemukan" jika tidak ada yang cocok
            if (!found && keyword.length > 0) {
                dosenTableBody.insertAdjacentHTML('beforeend', noResultsMessage);
            } else if (!found && keyword.length === 0 && noDosenAvailableRow) {
                // Jika pencarian dikosongkan dan memang tidak ada data, tampilkan kembali pesan default
                noDosenAvailableRow.style.display = ''; 
            }
        });
    </script>

</body>


</html>