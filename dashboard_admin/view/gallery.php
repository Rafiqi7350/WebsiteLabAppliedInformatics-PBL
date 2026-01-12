<?php
include '../model/config_db.php';
include '../model/middleware.php';

$result = pg_query($conn, "SELECT * FROM gallery ORDER BY id DESC");
$has_data = $result && pg_num_rows($result) > 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Gallery</title>

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

    /* Styling untuk gambar di tabel */
    .gallery-thumb {
      width: 60px;
      height: 60px;
      object-fit: cover;
    }

    /* Styling untuk preview gambar di modal */
    #preview_gambar {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 8px;
    }

    /* Mengatur lebar kolom agar tabel lebih rapi */
    #galleryTable th:nth-child(1),
    #galleryTable td:nth-child(1) {
      width: 5%;
    }

    #galleryTable th:nth-child(2),
    #galleryTable td:nth-child(2) {
      width: 10%;
    }

    #galleryTable th:nth-child(5),
    #galleryTable td:nth-child(5) {
      width: 25%;
    }

    .description-cell {
      max-width: 250px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
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
      <h4 class="fw-bold mb-0 text">Manajemen Galeri</h4>

      <div class="d-flex align-items-center gap-3">
        <div class="position-relative">
          <i class="bi bi-search search-icon"></i>
          <input type="text" id="searchInput" class="form-control" placeholder="Cari Judul">
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGalleryModal" style="width: 150px;">
          <i class="bi bi-plus-lg"></i> Tambah Foto
        </button>
      </div>
    </div>

    <div class="card shadow-sm p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0" id="galleryTable">
          <thead class="table-primary">
            <tr>
              <th>No</th>
              <th>Gambar</th>
              <th>Judul</th>
              <th>Deskripsi</th>
              <th>Aksi</th>
            </tr>
          </thead>

          <tbody id="galleryTableBody">
            <?php
            if ($has_data) :
              $no = 1;
              // Reset pointer result jika sudah digunakan di cek $has_data
              pg_result_seek($result, 0);
              while ($row = pg_fetch_assoc($result)) {
                $image_src = "../assets/img/gallery/" . htmlspecialchars($row['gambar']);
                $image_display = file_exists($image_src) ? $image_src : 'https://via.placeholder.com/60x60?text=No+Img';
            ?>
                <tr class="search-row"
                  data-judul="<?= htmlspecialchars($row['judul']) ?>"
                  data-deskripsi="<?= htmlspecialchars($row['deskripsi']) ?>">

                  <td><?= $no++ ?></td>

                  <td>
                    <img src="<?= $image_display ?>"
                      alt="<?= htmlspecialchars($row['judul']) ?>"
                      class="gallery-thumb rounded border">
                  </td>

                  <td class="fw-semibold"><?= htmlspecialchars($row['judul']) ?></td>
                  <td class="description-cell text-muted small" title="<?= htmlspecialchars($row['deskripsi']) ?>">
                    <?= htmlspecialchars($row['deskripsi']) ?>
                  </td>

                  <td class="text-nowrap">
                    <button class="btn btn-warning btn-sm editBtn text-white"
                      data-id="<?= htmlspecialchars($row['id']) ?>"
                      data-judul="<?= htmlspecialchars($row['judul']) ?>"
                      data-deskripsi="<?= htmlspecialchars($row['deskripsi']) ?>"
                      data-gambar="<?= htmlspecialchars($row['gambar']) ?>"
                      data-bs-toggle="modal"
                      data-bs-target="#editGalleryModal">
                      <i class="bi bi-pencil">
                        Edit
                      </i>
                    </button>

                    <a href="../controller/delete_gallery.php?id=<?= urlencode($row['id']) ?>"
                      onclick="return confirm('Yakin hapus foto: <?= addslashes($row['judul']) ?>?')"
                      class="btn btn-danger btn-sm">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>

                </tr>
              <?php }
            else : ?>
              <tr id="no-data-available">
                <td colspan="5" class="text-center text-muted py-4">
                  <i class="bi bi-info-circle me-2"></i> Belum ada foto di galeri.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>

        </table>
      </div>
    </div>

  </div>

  <div class="modal fade" id="addGalleryModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content shadow">
        <form action="../controller/add_gallery_action.php" method="POST" enctype="multipart/form-data">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Tambah Foto</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">

            <div class="mb-3">
              <label class="form-label">Judul</label>
              <input type="text" name="judul" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Deskripsi (Opsional)</label>
              <textarea name="deskripsi" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-0">
              <label class="form-label">Gambar (Wajib)</label>
              <input type="file" name="gambar" class="form-control file-validate" accept="image/*" required>
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Tambah</button>
          </div>

        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editGalleryModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content shadow">

        <form action="../controller/update_gallery.php" method="POST" enctype="multipart/form-data">

          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Edit Gallery Item</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" id="edit_id">

            <div class="text-center mb-3">
              <img id="preview_gambar" src="" alt="Photo Preview" class="rounded border shadow-sm">
            </div>

            <div class="mb-3">
              <label class="form-label">Judul</label>
              <input type="text" name="judul" id="edit_judul" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-0">
              <label class="form-label">Ganti Gambar (Opsional)</label>
              <input type="file" name="gambar" id="edit_gambar_input" class="form-control file-validate" accept="image/*">
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>

        </form>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // ===========================
    //  FILL EDIT DATA GALLERY
    // ===========================
    document.querySelectorAll('.editBtn').forEach(btn => {
      btn.addEventListener('click', function() {
        const fotoNama = this.dataset.gambar;
        let fotoPath = "../assets/img/gallery/" + fotoNama;

        // Fallback jika gambar tidak ada
        if (!fotoNama || fotoNama === 'null' || fotoNama === '') {
          fotoPath = 'https://via.placeholder.com/120x120?text=No+Photo';
        }

        document.getElementById("edit_id").value = this.dataset.id;
        document.getElementById("edit_judul").value = this.dataset.judul;
        document.getElementById("edit_deskripsi").value = this.dataset.deskripsi;
        document.getElementById("preview_gambar").src = fotoPath;
        document.getElementById("edit_gambar_input").value = ""; // Reset input file
      });
    });

    // ===========================
    //  PREVIEW GAMBAR BARU
    // ===========================
    document.getElementById("edit_gambar_input").addEventListener("change", function(e) {
      const file = e.target.files[0];

      if (file) {
        const reader = new FileReader();

        reader.onload = event => {
          document.getElementById("preview_gambar").src = event.target.result;
        };

        reader.readAsDataURL(file);
      }
    });

    // ===========================
    //  FILE VALIDATION (Simple)
    // ===========================
    document.querySelectorAll(".file-validate").forEach(input => {
      input.addEventListener("change", function() {
        const file = this.files[0];
        if (!file) return;

        const allowedTypes = ["image/jpeg", "image/png", "image/jpg", "image/webp"];
        if (!allowedTypes.includes(file.type)) {
          alert("Format file tidak valid! Gunakan JPG, PNG, atau WEBP.");
          this.value = "";
          return;
        }
      });
    });


    // ===========================
    //  SEARCH
    // ===========================
    const searchInput = document.getElementById("searchInput");
    const tableBody = document.getElementById("galleryTableBody");
    const rows = document.querySelectorAll(".search-row");
    const noDataRow = document.getElementById("no-data-available");
    const noResultsMessage = `
            <tr id="no-search-result">
                <td colspan="5" class="text-center text-muted py-4">
                    <i class="bi bi-search me-2"></i> Tidak ada hasil yang ditemukan.
                </td>
            </tr>
        `;

    searchInput.addEventListener("input", function() {
      const keyword = this.value.toLowerCase();
      let found = false;

      // Hapus pesan "tidak ditemukan" yang ada
      const existingNoRow = document.getElementById("no-search-result");
      if (existingNoRow) {
        existingNoRow.remove();
      }

      // Sembunyikan pesan "Belum ada data" jika ada
      if (noDataRow) {
        noDataRow.style.display = 'none';
      }


      rows.forEach(row => {
        const judul = row.dataset.judul.toLowerCase();
        const deskripsi = row.dataset.deskripsi.toLowerCase();

        const isMatch = judul.includes(keyword) || deskripsi.includes(keyword);

        row.style.display = isMatch ? "" : "none";

        if (isMatch) {
          found = true;
        }
      });

      // Tampilkan pesan "tidak ditemukan" jika tidak ada yang cocok
      if (!found && keyword.length > 0) {
        tableBody.insertAdjacentHTML('beforeend', noResultsMessage);
      } else if (!found && keyword.length === 0 && noDataRow) {
        // Jika pencarian dikosongkan dan memang tidak ada data, tampilkan kembali pesan default
        noDataRow.style.display = '';
      }
    });
  </script>

</body>

</html>