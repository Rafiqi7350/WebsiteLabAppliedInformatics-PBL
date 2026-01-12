<?php
include __DIR__ . '/../model/config_db.php';
include __DIR__ . '/../model/model_karya.php';
include __DIR__ . '/../model/model_membersDosen.php';
include '../model/middleware.php';

// Ambil semua dosen
$dosen_list = getAllMembersDosen($conn);

// Jika filter dosen dipilih
$filter_dosen = isset($_GET['filter']) ? $_GET['filter'] : "";

// Ambil publikasi (full atau berdasarkan dosen)
if ($filter_dosen !== "") {
  $result = getPublikasiByDosen($conn, $filter_dosen); // Pastikan kamu punya fungsi ini di model
} else {
  $result = getAllPublikasi($conn);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Publikasi - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../template/style_sidebar.css">

  <style>
    .content {
      padding: 20px;
    }

    h4.text {
      color: #083b71;
    }
  </style>
</head>

<body>
  <?php include __DIR__ . '/../template/topbar.php'; ?>
  <?php include __DIR__ . '/../template/sidebar.php'; ?>

  <div id="content" class="content">
    <div class="container-fluid p-3">

      <h4 class="fw-bold mb-4 text">Manajemen Publikasi</h4>

      <!-- ===================== TAMBAH PUBLIKASI ===================== -->
      <div class="card shadow-sm p-4 mb-5">
        <h5 class="fw-bold mb-3">Tambah Publikasi Baru</h5>

        <form action="../controller/add_publikasi_action.php" method="POST">
          <div class="row g-3">

            <div class="col-md-4">
              <label class="form-label">Member Dosen (Opsional)</label>
              <select name="member_dosen_id" class="form-select">
                <option value="">-- Pilih Dosen --</option>
                <?php while ($d = pg_fetch_assoc($dosen_list)) : ?>
                  <option value="<?= $d['id'] ?>">
                    <?= htmlspecialchars($d['nama']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Judul Publikasi</label>
              <input type="text" name="judul" class="form-control" required placeholder="Judul Publikasi">
            </div>

            <div class="col-md-2">
              <label class="form-label">Tahun</label>
              <input type="number" name="tahun" class="form-control" min="1900" max="<?= date('Y') ?>" value="<?= date('Y') ?>" required>
            </div>
          </div>

          <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-plus-lg"></i> Tambah Publikasi
            </button>
          </div>
        </form>
      </div>

      <!-- ===================== PUBLIKASI BERDASARKAN DOSEN ===================== -->
       <h4 class="fw-bold mb-4 text">Daftar Publikasi</h4>
      <div class="card shadow-sm p-4 mb-4">

        <div class="d-flex justify-content-between align-items-center mb-3"></div>

        <!-- FILTER -->
        <form method="GET" class="mb-3">
          <div class="row g-3">
            <div class="col-md-4 mt-1">
              <label class="form-label fw-semibold">Pilih Dosen</label>
              <select name="filter" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Dosen --</option>

                <?php
                pg_result_seek($dosen_list, 0);
                while ($dosen = pg_fetch_assoc($dosen_list)) :
                ?>
                  <option value="<?= $dosen['id'] ?>" <?= $filter_dosen == $dosen['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($dosen['nama']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
        </form>

        <!-- JUDUL KETIKA FILTER -->
        <?php if ($filter_dosen !== "") : ?>
          <div class="alert alert-info py-2 mb-3">
            Menampilkan publikasi milik:
            <strong>
              <?php
              pg_result_seek($dosen_list, 0);
              while ($dn = pg_fetch_assoc($dosen_list)) {
                if ($dn["id"] == $filter_dosen) {
                  echo htmlspecialchars($dn["nama"]);
                  break;
                }
              }
              ?>
            </strong>
          </div>
        <?php endif; ?>


        <!-- ===================== TABEL PUBLIKASI ===================== -->
        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle mb-0">
            <thead class="table-primary">
              <tr>
                <th width="5%">No</th>
                <th>Judul Publikasi</th>
                <th width="10%">Tahun</th>
                <th width="10%">Aksi</th>
              </tr>
            </thead>
            <tbody>

              <?php
              $i = 1;
              if ($result && pg_num_rows($result) > 0) :
                while ($row = pg_fetch_assoc($result)) :
              ?>
                  <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['tahun']) ?></td>
                    <td>
                      <a class="btn btn-sm btn-danger"
                        href="../controller/publikasi_delete.php?id=<?= (int)$row['id'] ?>"
                        onclick="return confirm('Yakin ingin menghapus publikasi ini?');">
                        <i class="bi bi-trash"></i> Hapus
                      </a>
                    </td>
                  </tr>
                <?php endwhile;
              else : ?>
                <tr>
                  <td colspan="4" class="text-center py-4 text-muted">
                    <i class="bi bi-info-circle me-2"></i>
                    Tidak ada publikasi ditemukan.
                  </td>
                </tr>
              <?php endif; ?>

            </tbody>
          </table>
        </div>

      </div>


</body>

</html>