<?php
// Include config dan model dengan path yang pasti
include __DIR__ . '/../model/config_db.php';
include __DIR__ . '/../model/model_partner.php';
include '../model/middleware.php';

// Mapping kategori → folder
$folderMap = [
    "Industry Partner"          => "industry_partner",
    "Educational Institutions"  => "educational_institutions",
    "Governement Institutions"  => "government_institutions",
    "International Institutions" => "international_institutions"
];

// Debug koneksi
if (!$conn) {
    die("Database connection failed: " . pg_last_error($conn));
}

// Ambil semua partner
$result = getAllPartners($conn);
if (!$result) {
    die("Query failed: " . pg_last_error($conn));
}

$has_partners = pg_num_rows($result) > 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Partners</title>

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

        .logo-display {
            width: 100px;
            height: 60px;
            object-fit: contain;
            border: 1px solid #eee;
            border-radius: 4px;
            padding: 5px;
        }

        .logo-preview-modal {
            width: 150px;
            height: 100px;
            object-fit: contain;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 10px;
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
            
            <h4 class="fw-bold mb-0 text">Manajemen Mitra</h4>
            <div class="d-flex align-items-center gap-3">
                <!-- SEARCH -->
                <div class="position-relative">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari Nama atau Kategori">
                </div>
           


                <!-- BUTTON TAMBAH -->
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPartnerModal" style="width: 150px;">
                    <i class="bi bi-plus-lg"></i> Tambah Mitra
                </button>
            </div>

        </div>

        <div class="card shadow-sm p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0" id="partnerTable">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 150px;">Logo</th>
                            <th style="width: 40%;">Nama</th>
                            <th style="width: 25%;">Kategori</th>
                            <th style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($has_partners) :
                            $no = 1;
                            while ($row = pg_fetch_assoc($result)) :
                                $folder = $folderMap[$row['kategori']] ?? 'others';
                                $logo_src = "../assets/img/partners/" . $folder . "/" . $row['logo'];
                                // Fallback jika logo tidak ditemukan
                                if (!file_exists($logo_src) || empty($row['logo'])) {
                                    $logo_src = 'https://via.placeholder.com/100x60?text=No+Logo';
                                }
                        ?>
                                <tr class="partner-row">
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <img src="<?= $logo_src ?>" alt="<?= htmlspecialchars($row['nama']) ?> Logo" class="logo-display">
                                    </td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($row['kategori']) ?></span></td>
                                    <td class="text-nowrap">
                                        <button class="btn btn-warning btn-sm editBtn text-white"
                                            data-id="<?= htmlspecialchars($row['id']) ?>"
                                            data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                            data-kategori="<?= htmlspecialchars($row['kategori']) ?>"
                                            data-folder="<?= htmlspecialchars($folder) ?>"
                                            data-logo="<?= htmlspecialchars($row['logo']) ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal">
                                            <i class="bi bi-pencil">
                                                Edit
                                            </i>
                                        </button>

                                        <a href="../controller/delete_partner.php?id=<?= urlencode($row['id']) ?>" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus partner <?= addslashes($row['nama']) ?>?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle me-2"></i> Belum ada data partner yang terdaftar.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPartnerModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="../controller/add_partner_action.php" method="POST" enctype="multipart/form-data" class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Mitra </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Partner</label>
                        <input name="nama" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="Industry Partner">Industry Partner</option>
                            <option value="Educational Institutions">Educational Institutions</option>
                            <option value="Governement Institutions">Governement Institutions</option>
                            <option value="International Institutions">International Institutions</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Logo (JPG, PNG, WEBP)</label>
                        <input type="file" name="logo" class="form-control file-validate" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="../controller/update_partner.php" method="POST" enctype="multipart/form-data" class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Partner</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="mb-4">
                        <img id="edit_logo_preview" src="" alt="Logo Preview" class="logo-preview-modal">
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label">Nama Partner</label>
                        <input type="text" class="form-control" name="nama" id="edit_nama" required>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label">Kategori</label>
                        <select class="form-select" name="kategori" id="edit_kategori" required>
                            <option value="Industry Partner">Industry Partner</option>
                            <option value="Educational Institutions">Educational Institutions</option>
                            <option value="Governement Institutions">Governement Institutions</option>
                            <option value="International Institutions">International Institutions</option>
                        </select>
                    </div>

                    <div class="mb-0 text-start">
                        <label class="form-label">Ganti Logo (Opsional)</label>
                        <input type="file" class="form-control file-validate" name="logo" id="edit_logo_input" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ==================== EDIT MODAL DATA FILL ====================
        document.querySelectorAll(".editBtn").forEach(btn => {
            btn.addEventListener("click", () => {
                const folder = btn.dataset.folder;
                const logo = btn.dataset.logo;

                document.getElementById("edit_id").value = btn.dataset.id;
                document.getElementById("edit_nama").value = btn.dataset.nama;
                document.getElementById("edit_kategori").value = btn.dataset.kategori;
                document.getElementById("edit_logo_input").value = ""; // Reset input file

                let logoSrc = `../assets/img/partners/${folder}/${logo}`;
                if (!logo || logo === 'null' || logo === '') {
                    // Fallback jika logo kosong
                    logoSrc = 'https://via.placeholder.com/150x100?text=No+Logo';
                }

                document.getElementById("edit_logo_preview").src = logoSrc;
            });
        });

        // ==================== LOGO PREVIEW & VALIDATION ====================
        function setupValidationAndPreview(inputId, previewId) {
            document.getElementById(inputId).addEventListener("change", function(event) {
                const file = event.target.files[0];
                const previewImg = document.getElementById(previewId);

                if (!file) return;

                const allowed = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
                if (!allowed.includes(file.type)) {
                    alert("Format file tidak valid! Hanya JPG, PNG, WEBP.");
                    event.target.value = "";
                    previewImg.src = 'https://via.placeholder.com/150x100?text=Invalid+File';
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert("Ukuran file maksimal 2 MB!");
                    event.target.value = "";
                    previewImg.src = 'https://via.placeholder.com/150x100?text=File+Too+Large';
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = e => previewImg.src = e.target.result;
                reader.readAsDataURL(file);
            });
        }

        // Setup validation for Edit Modal
        setupValidationAndPreview("edit_logo_input", "edit_logo_preview");
        document.querySelector("#addPartnerModal input[type='file']").addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const allowed = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
            if (!allowed.includes(file.type)) {
                alert("Format file tidak valid! Hanya JPG, PNG, WEBP.");
                event.target.value = "";
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert("Ukuran file maksimal 2 MB!");
                event.target.value = "";
                return;
            }
        });


        // ==================== SEARCH ====================
        document.getElementById("searchInput").addEventListener("keyup", function() {
            const value = this.value.toLowerCase();
            let found = false;

            document.querySelectorAll("#partnerTable tbody tr").forEach(row => {
                const textContent = row.textContent.toLowerCase();
                const isMatch = textContent.includes(value);

                row.style.display = isMatch ? "" : "none";

                if (isMatch) {
                    found = true;
                }
            });
            // ==================== FILTER KATEGORI ====================
            document.getElementById("filterKategori").addEventListener("change", function() {
                const filterValue = this.value.toLowerCase();
                const searchValue = document.getElementById("searchInput").value.toLowerCase();

                document.querySelectorAll("#partnerTable tbody tr").forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    const rowKategori = row.querySelector("td:nth-child(4)").innerText.toLowerCase();

                    const matchKategori = filterValue === "" || rowKategori.includes(filterValue);
                    const matchSearch = rowText.includes(searchValue);

                    row.style.display = (matchKategori && matchSearch) ? "" : "none";
                });
            });


        });
    </script>
</body>

</html>