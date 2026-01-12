<?php
require_once __DIR__ . '/../model/config_db.php';

// MODELS
require_once __DIR__ . '/../model/model_profile.php';
require_once __DIR__ . '/../model/model_topik.php';
require_once __DIR__ . '/../model/model_news.php';

// CONTROLLERS
require_once __DIR__ . '/../controller/profile_controller.php';
require_once __DIR__ . '/../controller/topik_controller.php';
include '../model/middleware.php';

$profileCtrl = new ProfileController($conn);
$topikCtrl   = new TopikController($conn);

// Process form, Menangani input form
$profileCtrl->handlePost();
$topikCtrl->handlePost();

// Fetch data untuk ditampilkan
$profile = $profileCtrl->getProfile();
$topik   = $topikCtrl->getAll();
$news    = getAllNews($conn);

// Mengolah data sebelum ditampilkan
$visi = $profile['visi'] ?? '';
$misiList = json_decode($profile['misi'] ?? '[]', true);
if (!is_array($misiList)) $misiList = [];

$deskripsi = $profile['deskripsi'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Info Profil</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../template/style_sidebar.css">

    <style>
        .news-card img {
            width: 100%;
            height: 130px;
            object-fit: cover;
        }

        .remove-misi {
            cursor: pointer;
        }

        /* Menyesuaikan lebar konten agar lebih rapi */
        .content {
            padding: 20px;
        }

        .card {
            border-left: 5px solid #007bff;
            /* Garis biru di samping card untuk visual */
        }
    </style>

</head>

<body>

    <?php include __DIR__ . '/../template/sidebar.php'; ?>


    <?php include __DIR__ . '/../template/topbar.php'; ?>

    <div id="content" class="content">

        <?php
        $kepala_query = pg_query($conn, "SELECT * FROM member_dosen WHERE LOWER(role) = 'kepala lab' LIMIT 1");
        $kepalalab = pg_fetch_assoc($kepala_query);
        ?>
        <div class="row justify-content-center">
            <div class="col-12 col-sm-6 col-md-4 mt-4 mb-5">
                <div class="card text-center shadow p-4">
                    <h5 class="mb-3">Kepala Laboratorium</h5>
                    <?php if (!empty($kepalalab)) :
                        $foto = "../assets/img/members/dosen/" . ($kepalalab['foto'] ?? '');
                        if (empty($kepalalab['foto']) || !file_exists($foto)) {
                            $foto = "../assets/img/member_dosen/kepala_lab.png";
                        }
                    ?>
                        <img src="<?= htmlspecialchars($foto); ?>" class="rounded-circle mx-auto mb-3 border border-3 border-primary"
                            width="120" height="120" style="object-fit: cover;">

                        <h6 class="mt-1 fw-bold text-primary"><?= htmlspecialchars(strtoupper($kepalalab['role'])); ?></h6>

                        <p class="mb-0 fw-semibold"><?= htmlspecialchars($kepalalab['nama']); ?></p>

                        <?php if (!empty($kepalalab['expertise'])) : ?>
                            <p class="text-muted small mb-2">
                                **Keahlian:** <?= htmlspecialchars($kepalalab['expertise']); ?>
                            </p>
                        <?php endif; ?>

                        <a href="members_dosen.php"
                            class="btn btn-outline-primary btn-sm mt-1">
                             Lihat Selengkapnya
                        </a>

                    <?php else : ?>
                        <img src="../assets/img/member_dosen/kepala_lab.png" class="rounded-circle mx-auto mb-3" width="120">

                        <h6 class="mt-3 fw-bold text-danger">KEPALA LAB</h6>
                        <p class="text-muted"><i>Belum ada Kepala Lab di database.</i></p>

                        <a href="members_dosen.php" class="btn btn-outline-danger btn-sm">Tambahkan</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card p-3 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-3">Deskripsi Laboratorium Applied Informatics</h5>
                <button class="btn btn-sm btn-warning text-white"
                    data-bs-toggle="modal" data-bs-target="#deskripsiModal">
                    <i class="bi bi-pencil"></i> Edit
                </button>
            </div>
            <hr>

            <?php if (!empty($deskripsi)) : ?>
                <p style="white-space: pre-wrap;"><?= htmlspecialchars($deskripsi) ?></p>
            <?php else : ?>
                <p class='text-muted'><i>Belum ada deskripsi yang ditambahkan.</i></p>
            <?php endif; ?>
        </div>


        <div class="card p-3 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Visi & Misi Laboratorium</h5>
                <button class="btn btn-sm btn-warning text-white"
                    data-bs-toggle="modal" data-bs-target="#visiMisiModal">
                    <i class="bi bi-pencil"></i> Edit
                </button>
            </div>

            <hr>

            <h6 class="fw-bold">Visi</h6>
            <p><?= $visi ? nl2br(htmlspecialchars($visi)) : "<i class='text-muted'>Belum ada visi.</i>" ?></p>

            <h6 class="mt-3 fw-bold">Misi</h6>
            <?php if (!empty($misiList)) { ?>
                <ul>
                    <?php foreach ($misiList as $m) { ?>
                        <li><?= htmlspecialchars($m) ?></li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p class="text-muted"><i>Belum ada misi.</i></p>
            <?php } ?>
        </div>

        <div class="card p-3 mb-4">
            <h5 class="mb-3">Berita Terbaru Laboratory Applied Informatics</h5>
            <div class="row g-3">
                <?php $news_count = 0; ?>
                <?php while ($n = pg_fetch_assoc($news)) { ?>
                    <?php if ($news_count >= 3) break; // Batasi maksimal 3 berita 
                    ?>
                    <div class="col-12 col-md-4">
                        <div class="news-card border rounded p-2 shadow-sm">
                            <img src="../assets/img/news/<?= htmlspecialchars($n['gambar']) ?>" alt="<?= htmlspecialchars($n['judul']) ?>" class="rounded">
                            <div class="mt-2">
                                <strong class="d-block text-truncate"><?= htmlspecialchars($n['judul']) ?></strong>
                                <small class="text-muted"><?= date('d M Y', strtotime($n['tanggal'])) ?></small>
                            </div>
                        </div>
                    </div>
                    <?php $news_count++; ?>
                <?php } ?>
                <?php if (pg_num_rows($news) == 0): ?>
                    <p class='text-muted'><i>Belum ada berita yang ditambahkan.</i></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card p-3 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Priority Research Topics</h5>
                <button class="btn btn-sm btn-primary text-white"
                    data-bs-toggle="modal" data-bs-target="#addTopikModal">+ Tambah Topik</button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover mt-3">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        // Reset pointer news (karena sudah digunakan sebelumnya)
                        if (pg_num_rows($topik) > 0) {
                            pg_result_seek($topik, 0);
                        }

                        if (pg_num_rows($topik) > 0) :
                            while ($t = pg_fetch_assoc($topik)) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($t['judul']) ?></td>
                                    <td><?= htmlspecialchars(substr($t['deskripsi'], 0, 100)) . (strlen($t['deskripsi']) > 100 ? '...' : '') ?></td>
                                    <td class="text-nowrap">
                                        <button class="btn btn-warning btn-sm text-white"
                                            onclick='openEditModal(<?= json_encode($t["id"]) ?>, <?= json_encode($t["judul"]) ?>, <?= json_encode($t["deskripsi"]) ?>)'>
                                            <i class="bi bi-pencil">Edit</i>
                                        </button>

                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Apakah anda yakin untuk menghapus topik ini?')">
                                            <input type="hidden" name="id_topik" value="<?= htmlspecialchars($t['id']) ?>">
                                            <button name="delete_topik" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile;
                        else : ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted"><i>Belum ada topik penelitian yang ditambahkan.</i></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="modal fade" id="editTopikModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Topik</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id_topik" id="edit_id_modal">
                    <input type="hidden" name="edit_topik" value="1">

                    <div class="mb-3">
                        <label for="edit_judul_modal" class="form-label">Judul</label>
                        <input type="text" name="judul" id="edit_judul_modal" class="form-control" required>
                    </div>

                    <div class="mb-0">
                        <label for="edit_deskripsi_modal" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi_modal" class="form-control" rows="4" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="visiMisiModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Visi & Misi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="visi_textarea" class="form-label">Visi</label>
                        <textarea name="visi" id="visi_textarea" class="form-control" rows="3"><?= htmlspecialchars($visi) ?></textarea>
                    </div>

                    <label class="form-label">Misi</label>
                    <div id="misiContainer">
                        <?php foreach ($misiList as $m) { ?>
                            <div class="input-group mb-2">
                                <input type="text" name="misi[]" class="form-control" value="<?= htmlspecialchars($m) ?>" required>
                                <button type="button" class="btn btn-outline-danger remove-misi">&times;</button>
                            </div>
                        <?php } ?>
                    </div>

                    <button type="button" id="addMisiBtn" class="btn btn-outline-primary btn-sm mt-2"><i class="bi bi-plus"></i> Tambah Misi</button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button name="save_visi_misi" type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="addTopikModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Topik</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_judul" class="form-label">Judul</label>
                        <input type="text" name="judul" id="add_judul" class="form-control" required>
                    </div>
                    <div class="mb-0">
                        <label for="add_deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="add_deskripsi" class="form-control" rows="4"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button name="add_topik" type="submit" class="btn btn-primary">Tambah</button>
                </div>

            </form>
        </div>
    </div>

    <div class="modal fade" id="deskripsiModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit deskripsi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <label for="deskripsi_textarea" class="form-label">Deskripsi Laboratorium</label>
                    <textarea name="deskripsi" id="deskripsi_textarea" class="form-control" rows="6"><?= htmlspecialchars($deskripsi) ?></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button name="save_deskripsi" type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // open edit modal 
        function openEditModal(id, judul, deskripsi) {
            document.getElementById('edit_id_modal').value = id;
            document.getElementById('edit_judul_modal').value = judul;
            document.getElementById('edit_deskripsi_modal').value = deskripsi;
            // Gunakan `new bootstrap.Modal().show()` jika modal belum diinisialisasi, atau langsung `modal.show()` jika sudah.
            // Untuk memastikan, kita buat instance baru.
            const editModal = new bootstrap.Modal(document.getElementById('editTopikModal'));
            editModal.show();
        }

        // misi add/remove
        document.addEventListener("click", e => {
            if (e.target.id === "addMisiBtn") {
                const div = document.createElement("div");
                div.className = "input-group mb-2";
                div.innerHTML = `
                <input type="text" name="misi[]" class="form-control" required>
                <button type="button" class="btn btn-outline-danger remove-misi">&times;</button>
            `;
                document.getElementById("misiContainer").appendChild(div);
            }

            // Delegasi event untuk tombol remove-misi
            if (e.target.classList.contains("remove-misi")) {
                e.target.closest(".input-group").remove();
            }
        });
    </script>

</body>

</html>