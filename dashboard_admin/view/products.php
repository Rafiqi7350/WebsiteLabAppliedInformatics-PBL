<?php
include '../model/config_db.php';
include '../model/model_products.php';
include '../model/middleware.php';

$result = getAllProducts($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../template/style_sidebar.css">

    <style>
        /* Kontainer Utama */
        .content {
            padding: 20px;
        }

        /* Gambar Modal Preview */
        .modal-image-preview {
            width: 180px;
            height: 140px;
            object-fit: cover;
            border: 2px solid #ddd;
            border-radius: 8px;
        }

        /* Tabel Produk */
        #productTable {
            /* table-layout: fixed; <- Lebih baik dihindari jika memungkinkan, gunakan lebar persentase */
            width: 100%;
        }

        #productTable th,
        #productTable td {
            vertical-align: middle;
            /* Mengubah dari top ke middle untuk estetika */
            white-space: normal;
            word-wrap: break-word;
            padding: 8px;
        }

        /* KONTEN SCROLL DALAM SEL */
        .scroll-cell {
            max-height: 80px;
            overflow-y: auto;
            padding-right: 6px;
        }

        /* Lebar kolom (Menggunakan persentase agar lebih responsif) */
        #productTable th:nth-child(1),
        #productTable td:nth-child(1) {
            width: 5%;
        }

        /* No */
        #productTable th:nth-child(2),
        #productTable td:nth-child(2) {
            width: 10%;
        }

        /* Gambar */
        #productTable th:nth-child(3),
        #productTable td:nth-child(3) {
            width: 15%;
        }

        /* Nama */
        #productTable th:nth-child(4),
        #productTable td:nth-child(4) {
            width: 30%;
        }

        /* Deskripsi */
        #productTable th:nth-child(5),
        #productTable td:nth-child(5) {
            width: 25%;
        }

        /* Link */
        #productTable th:nth-child(6),
        #productTable td:nth-child(6) {
            width: 15%;
        }

        /* Aksi */

        /* Search input styling */
        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            pointer-events: none;
        }

        #searchInput {
            padding-left: 30px;
            width: 250px;
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
            <h4 class="fw-bold mb-0 text">Manajemen Produk</h4>

            <div class="d-flex align-items-center gap-3">
                <div class="position-relative">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari Nama Produk">
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal" style="width: 180px;">
                    <i class="bi bi-plus-lg"></i> Tambah Produk
                </button>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="productTable">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Link</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $no = 1;
                            $has_products = false;
                            while ($row = pg_fetch_assoc($result)) {
                                $has_products = true;
                                $image_path = "../assets/img/products/" . $row['gambar'];
                                // Fallback jika gambar tidak ada
                                $image_url = file_exists($image_path) ? $image_path : 'https://via.placeholder.com/50x50?text=No+Image';
                            ?>
                                <tr class="product-row"
                                    data-id="<?= htmlspecialchars($row['id']) ?>"
                                    data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                    data-deskripsi="<?= htmlspecialchars($row['deskripsi']) ?>"
                                    data-link="<?= htmlspecialchars($row['link']) ?>"
                                    data-gambar="<?= htmlspecialchars($row['gambar']) ?>">

                                    <td><?= $no++ ?></td>
                                    <td>
                                        <img src="<?= $image_url ?>" alt="<?= htmlspecialchars($row['nama']) ?>" width="50" height="50" style="object-fit: cover; border-radius: 4px;">
                                    </td>

                                    <td class="fw-semibold"><?= htmlspecialchars($row['nama']) ?></td>
                                    <td>
                                        <div class="scroll-cell text-muted small">
                                            <?= htmlspecialchars($row['deskripsi']) ?>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="scroll-cell">
                                            <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank" class="text-truncate d-block small">
                                                <?= htmlspecialchars(substr($row['link'], 0, 40)) . (strlen($row['link']) > 40 ? '...' : '') ?>
                                            </a>
                                        </div>
                                    </td>


                                    <td class="text-nowrap">
                                        <button class="btn btn-warning btn-sm editBtn text-white"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editProductModal">
                                            <i class="bi bi-pencil">Edit</i>
                                        </button>

                                        <a href="../controller/delete_products.php?id=<?= htmlspecialchars($row['id']) ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus produk <?= addslashes($row['nama']) ?>?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>

                            <?php if (!$has_products): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-info-circle me-2"></i>Belum ada produk yang ditambahkan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

<!-- Modal add Produuct -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="../controller/add_product_action.php"
                method="POST" enctype="multipart/form-data"
                class="modal-content shadow" id="addForm">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Link Produk (URL)</label>
                        <input type="url" name="link" class="form-control" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Gambar (JPG, PNG, maks 2MB)</label>
                        <input type="file" name="gambar" class="form-control file-validate" accept="image/jpeg, image/png, image/jpg" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-primary" type="submit">Tambah</button>
                </div>

            </form>
        </div>
    </div>

    <!-- Modal edit produk -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="../controller/update_product.php" method="POST"
                enctype="multipart/form-data" class="modal-content shadow" id="editForm">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body text-center">

                    <img id="edit_logo_preview" class="modal-image-preview mb-3" alt="Product Image Preview">

                    <input type="hidden" name="id" id="edit_id">

                    <div class="mb-3 text-start">
                        <label class="form-label">Nama Product</label>
                        <input type="text" name="nama" id="edit_nama" class="form-control" required>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label">Link Produk</label>
                        <input type="url" name="link" id="edit_link" class="form-control" required>
                    </div>

                    <div class="mb-0 text-start">
                        <label class="form-label">Ganti Gambar (Opsional | JPG, PNG, maks 2MB)</label>
                        <input type="file" name="gambar" id="edit_gambar_input" class="form-control file-validate" accept="image/jpeg, image/png, image/jpg">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                </div>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ==================== FILL EDIT MODAL ====================
        document.querySelectorAll('.editBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                let row = this.closest('.product-row');

                // Mendapatkan URL gambar, menggunakan data-gambar
                const imageFileName = row.dataset.gambar;
                const imagePath = imageFileName ? `../assets/img/products/${imageFileName}` : 'https://via.placeholder.com/180x140?text=No+Image';

                // **Koreksi ID DOM** (Ganti 'preview_gambar' menjadi 'edit_logo_preview')
                document.getElementById("edit_id").value = row.dataset.id;
                document.getElementById("edit_nama").value = row.dataset.nama;
                document.getElementById("edit_deskripsi").value = row.dataset.deskripsi;
                document.getElementById("edit_link").value = row.dataset.link;
                document.getElementById("edit_logo_preview").src = imagePath;

                // Kosongkan input file agar tidak mengirim file lama secara tidak sengaja
                document.getElementById("edit_gambar_input").value = "";
            });
        });

        // ==================== PREVIEW GAMBAR EDIT ====================
        document.getElementById("edit_gambar_input").addEventListener("change", function(e) {
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = event => {
                    // **Koreksi ID DOM**
                    document.getElementById("edit_logo_preview").src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // ==================== FILE VALIDATION ====================
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

                if (file.size > 2 * 1024 * 1024) {
                    alert("Ukuran maksimal file 2MB!");
                    this.value = "";
                }
            });
        });

        // ==================== SEARCH ====================
        const searchInput = document.getElementById("searchInput");
        const tableBody = document.querySelector("#productTable tbody");
        const rows = document.querySelectorAll(".product-row");
        const noProductRow = `
            <tr id="no-product-found">
                <td colspan="6" class="text-center text-muted py-4">
                    <i class="bi bi-exclamation-octagon me-2"></i>Tidak ada produk ditemukan.
                </td>
            </tr>
        `;

        searchInput.addEventListener("input", function() {
            let keyword = this.value.toLowerCase();
            let found = false;

            // Hapus pesan "tidak ditemukan" jika ada
            const existingNoRow = document.getElementById("no-product-found");
            if (existingNoRow) {
                existingNoRow.remove();
            }

            rows.forEach(row => {
                let nama = row.dataset.nama.toLowerCase();
                let desk = row.dataset.deskripsi.toLowerCase();

                const isMatch = nama.includes(keyword) || desk.includes(keyword);
                row.style.display = isMatch ? "" : "none";

                if (isMatch) {
                    found = true;
                }
            });

            // Tampilkan pesan "tidak ditemukan" jika tidak ada yang cocok
            if (!found) {
                tableBody.insertAdjacentHTML('beforeend', noProductRow);
            }
        });
    </script>

</body>

</html>