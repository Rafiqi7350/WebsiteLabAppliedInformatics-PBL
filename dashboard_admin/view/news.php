<?php
include '../model/config_db.php';
include '../model/model_news.php';
include '../model/middleware.php';

$result = getAllNews($conn);
$has_data = $result && pg_num_rows($result) > 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage News</title>

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

    .news-thumb {
      width: 55px;
      height: 55px;
      object-fit: cover;
    }

    #preview_gambar {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 8px;
    }

    .table th:nth-child(1),
    .table td:nth-child(1) {
      width: 5%;
    }

    .table th:nth-child(2),
    .table td:nth-child(2) {
      width: 10%;
    }

    .table th:nth-child(3),
    .table td:nth-child(3) {
      width: 25%;
    }

    .table th:nth-child(4),
    .table td:nth-child(4) {
      width: 20%;
    }

    .table th:nth-child(5),
    .table td:nth-child(5) {
      width: 15%;
    }

    .table th:nth-child(6),
    .table td:nth-child(6) {
      width: 15%;
    }

    .content-cell {
      max-width: 200px;
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
      <h4 class="fw-bold mb-0 text">Manajemen Berita</h4>

      <div class="d-flex align-items-center gap-3">
        <div class="position-relative">
          <i class="bi bi-search search-icon"></i>
          <input type="text" id="searchInput" class="form-control" placeholder="Cari Judul Berita">
        </div>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewsModal" style="width: 150px;">
          <i class="bi bi-plus-lg"></i> Tambah Berita
        </button>
      </div>
    </div>

    <div class="card shadow-sm p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
          <thead class="table-primary">
            <tr>
              <th>No</th>
              <th>Gambar</th>
              <th>Judul</th>
              <th>Link</th>
              <th>Isi</th>
              <th>Tanggal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="newsTableBody">

            <?php
            if ($has_data) :
              $no = 1;
              pg_result_seek($result, 0);

              while ($row = pg_fetch_assoc($result)) {
                $image_src = "../assets/img/news/" . htmlspecialchars($row['gambar']);
                $image_display = file_exists($image_src)
                  ? $image_src
                  : 'https://via.placeholder.com/55x55?text=No+Img';

                $truncated_isi = htmlspecialchars(substr($row['isi'], 0, 80)) . (strlen($row['isi']) > 80 ? '...' : '');
            ?>

                <tr class="news-row"
                  data-judul="<?= strtolower($row['judul']) ?>"
                  data-isi-full="<?= strtolower($row['isi']) ?>">

                  <td><?= $no++ ?></td>

                  <td>
                    <img src="<?= $image_display ?>" class="news-thumb rounded">
                  </td>

                  <td class="fw-semibold"><?= htmlspecialchars($row['judul']) ?></td>

                  <td class="fw-semibold">
                    <?php if (!empty($row['link'])): ?>
                      <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank" class="text-primary text-decoration-underline">
                        Lihat
                      </a>
                    <?php else: ?>
                      <span class="text-muted small"><i>Tidak ada</i></span>
                    <?php endif; ?>
                  </td>

                  <td class="content-cell text-muted small" title="<?= htmlspecialchars($row['isi']) ?>">
                    <?= $truncated_isi ?>
                  </td>

                  <td><?= date('d M Y H:i', strtotime($row['tanggal'])) ?></td>

                  <td class="text-nowrap">
                    <button class="btn btn-warning btn-sm editBtn text-white"
                      data-id="<?= $row['id'] ?>"
                      data-judul="<?= htmlspecialchars($row['judul']) ?>"
                      data-link="<?= htmlspecialchars($row['link'] ?? '') ?>"
                      data-isi="<?= htmlspecialchars($row['isi']) ?>"
                      data-gambar="<?= htmlspecialchars($row['gambar']) ?>"
                      data-tanggal="<?= date('Y-m-d\TH:i', strtotime($row['tanggal'])) ?>"
                      data-bs-toggle="modal"
                      data-bs-target="#editNewsModal">
                      <i class="bi bi-pencil"></i> Edit
                    </button>

                    <a href="../controller/delete_news.php?id=<?= urlencode($row['id']) ?>"
                      class="btn btn-danger btn-sm"
                      onclick="return confirm('Yakin hapus berita: <?= addslashes($row['judul']) ?>?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>
                </tr>

              <?php
              }
            else : ?>
              <tr id="no-data-available">
                <td colspan="7" class="text-center text-muted py-4">
                  <i class="bi bi-info-circle me-2"></i> Belum ada data berita.
                </td>
              </tr>
            <?php endif; ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- MODAL TAMBAH -->
  <div class="modal fade" id="addNewsModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content shadow">
        <form action="../controller/add_news_action.php" method="POST" enctype="multipart/form-data">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Tambah Berita</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Judul</label>
              <input type="text" name="judul" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Isi</label>
              <textarea name="isi" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Link (Opsional)</label>
              <input type="url" name="link" class="form-control" placeholder="https://contoh.com">
            </div>

            <div class="mb-3">
              <label class="form-label">Thumbnail</label>
              <input type="file" name="gambar" class="form-control file-validate" accept="image/*" required>
            </div>

            <div class="mb-0">
              <label class="form-label">Tanggal & Waktu</label>
              <input type="datetime-local" name="tanggal" class="form-control" required value="<?= date('Y-m-d\TH:i'); ?>">
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

  <!-- MODAL EDIT -->
  <div class="modal fade" id="editNewsModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content shadow">
        <form action="../controller/update_news.php" method="POST" enctype="multipart/form-data">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Edit News</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">

            <input type="hidden" name="id" id="edit_id">
            <input type="hidden" name="gambar_lama" id="edit_gambar_lama">

            <div class="text-center mb-3">
              <img id="preview_gambar" src="" alt="Thumbnail Preview" class="rounded border shadow-sm">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Judul</label>
              <input type="text" name="judul" id="edit_judul" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Link (Opsional)</label>
              <input type="url" name="link" id="edit_link" class="form-control" placeholder="https://contoh.com">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Isi</label>
              <textarea name="isi" id="edit_isi" class="form-control" rows="5" required></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Ganti Gambar (Opsional)</label>
              <input type="file" name="gambar" id="edit_gambar_input" class="form-control file-validate" accept="image/*">
            </div>

            <div class="mb-0">
              <label class="form-label fw-semibold">Tanggal & Waktu</label>
              <input type="datetime-local" name="tanggal" id="edit_tanggal" class="form-control" required>
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
    document.querySelectorAll(".file-validate").forEach(input => {
      input.addEventListener("change", function() {
        const file = this.files[0];
        if (!file) return;

        const allowed = ["image/jpeg", "image/png", "image/jpg", "image/webp"];
        if (!allowed.includes(file.type)) {
          alert("Format tidak valid!");
          this.value = "";
        }

        if (file.size > 10 * 1024 * 1024) {
          alert("Ukuran maksimal 10MB!");
          this.value = "";
        }
      });
    });

    document.querySelectorAll('.editBtn').forEach(btn => {
      btn.addEventListener('click', function() {
        const fotoNama = this.dataset.gambar;

        let fotoPath = "../assets/img/news/" + fotoNama;

        if (!fotoNama) {
          fotoPath = "https://via.placeholder.com/120x120?text=No+Photo";
        }

        document.getElementById("edit_id").value = this.dataset.id;
        document.getElementById("edit_judul").value = this.dataset.judul;
        document.getElementById("edit_link").value = this.dataset.link || "";
        document.getElementById("edit_isi").value = this.dataset.isi;
        document.getElementById("edit_tanggal").value = this.dataset.tanggal;
        document.getElementById("preview_gambar").src = fotoPath;
        document.getElementById("edit_gambar_input").value = "";
        document.getElementById("edit_gambar_lama").value = fotoNama;
      });
    });

    document.getElementById("edit_gambar_input").addEventListener("change", function(e) {
      const file = e.target.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = event => {
        document.getElementById("preview_gambar").src = event.target.result;
      };
      reader.readAsDataURL(file);
    });

    document.getElementById("searchInput").addEventListener("input", function() {
      const keyword = this.value.toLowerCase();
      const rows = document.querySelectorAll(".news-row");

      rows.forEach(row => {
        const judul = row.dataset.judul; 

        row.style.display = judul.includes(keyword) ? "" : "none";
      });
    });
  </script>

</body>

</html>