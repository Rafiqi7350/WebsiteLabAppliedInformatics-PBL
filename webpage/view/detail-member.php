<?php
include '../../dashboard_admin/model/config_db.php';

// Ambil param
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$type = isset($_GET['type']) ? strtolower($_GET['type']) : 'dosen';

if ($id <= 0) {
    die("ID tidak valid.");
}

// Escape helper
function e($s)
{
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

$img_base_dosen = '../../dashboard_admin/assets/img/members/dosen/';
$img_base_mhs = '../../dashboard_admin/assets/img/members/mahasiswa/';

// DETAIL DOSEN - PAKAI VIEW
if ($type === 'dosen') {
    // Ambil dari VIEW (1 query aja, dapat semua data!)
    $q = 'SELECT * FROM view_detail_dosen WHERE id = $1 LIMIT 1';
    $res = pg_query_params($conn, $q, [$id]);

    if (!$res || pg_num_rows($res) == 0) {
        die("Dosen tidak ditemukan.");
    }

    $dosen = pg_fetch_assoc($res);

    // Decode JSON dari VIEW
    $publikasi_list = json_decode($dosen['publikasi'], true);
    $riset_list = json_decode($dosen['riset'], true);
    $ki_list = json_decode($dosen['kekayaan_intelektual'], true);
    $ppm_list = json_decode($dosen['ppm'], true);

    // Ambil sosial media
    $socials = [
        'scholar' => $dosen['scholar_link'],
        'sinta'   => $dosen['sinta_link'],
        'scopus'  => $dosen['scopus_link'],
        'orcid'   => $dosen['orcid_link']
    ];
}
?>


<!-- navbar -->
<?php include __DIR__ . '/../template/navbar.php'; ?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Member - Lab Applied Informatics</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Style detail member -->
    <link rel="stylesheet" href="../template/style_detailMember.css">
    <!-- Style dropdown -->
    <link rel="stylesheet" href="../template/style_dropdown.css?v=1.0">
</head>

<body>
    <!-- HERO -->
    <div class="hero">DETAIL MEMBER</div>
    <!-- WRAPPER -->
    <div class="detail-wrapper">
        <!-- BACK BUTTON -->
        <a href="members.php" class="back-button" title="Kembali ke Member">
            <i class="fas fa-arrow-left"></i>
        </a>

        <!-- CONTAINER -->
        <div class="detail-container">
            <?php if ($type === 'dosen'): ?>

                <!-- PROFILE CARD DOSEN -->
                <div class="profile-card">
                    <img src="<?= file_exists($img_base_dosen . $dosen['foto']) ?
                                    $img_base_dosen . e($dosen['foto']) :
                                    $img_base_dosen . 'default.jpg' ?>"
                        class="profile-photo"
                        alt="<?= e($dosen['nama']) ?>">

                    <div class="profile-info">
                        <div class="profile-info-header">
                            <h2><?= e($dosen['nama']) ?></h2>
                        </div>
                        <div class="role"><?= e($dosen['role']) ?></div>
                        <div class="expertise"><?= e($dosen['expertise']) ?></div>

                        <!-- SOCIAL MEDIA -->
                        <div class="social-media-wrapper">
                            <p class="sm-title">Follow Social Media</p>
                            <div class="social-icons">
                                <?php
                                $social_links = [
                                    'scholar' => ['icon' => 'fa-graduation-cap', 'link' => $socials['scholar']],
                                    'sinta'   => ['icon' => 'fa-book', 'link' => $socials['sinta']],
                                    'scopus'  => ['icon' => 'fa-search', 'link' => $socials['scopus']],
                                    'orcid'   => ['icon' => 'fa-id-card', 'link' => $socials['orcid']]
                                ];

                                $has_social = false;
                                foreach ($social_links as $platform => $data):
                                    if (!empty($data['link'])):
                                        $has_social = true;
                                        echo '<a href="' . e($data['link']) . '" target="_blank" title="' . ucfirst($platform) . '">
                                        <i class="fas ' . $data['icon'] . '"></i></a>';
                                    endif;
                                endforeach;

                                if (!$has_social):
                                    echo '<span class="no-social">Belum ada</span>';
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB NAVIGATION -->
                <div class="tab-navigation">
                    <ul class="nav nav-pills" id="memberTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#deskripsi" type="button">
                                Deskripsi
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#publikasi" type="button">
                                Publikasi
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#riset" type="button">
                                Riset
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#ki" type="button">
                                Kekayaan Intelektual
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#ppm" type="button">
                                PPM
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- TAB CONTENT -->
                <div class="tab-content">

                    <!-- DESKRIPSI -->
                    <div class="tab-pane fade show active" id="deskripsi">
                        <div class="content-card">
                            <h5 class="mb-3">Deskripsi</h5>
                            <p class="tab-content-text">
                                <?= !empty($dosen['deskripsi']) ? nl2br(e($dosen['deskripsi'])) : 'Belum ada deskripsi.' ?>
                            </p>
                        </div>
                    </div>

                    <!-- PUBLIKASI -->
                    <div class="tab-pane fade" id="publikasi">
                        <div class="content-card">
                            <h5 class="mb-3">Publikasi</h5>
                            <?php if (!empty($publikasi_list)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 60px;">No</th>
                                                <th>Judul</th>
                                                <th style="width: 100px;">Tahun</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($publikasi_list as $pub): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= e($pub['judul']) ?></td>
                                                    <td><?= e($pub['tahun']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-message">
                                    <i class="fas fa-file-alt"></i>
                                    <p>Belum ada publikasi</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- RISET -->
                    <div class="tab-pane fade" id="riset">
                        <div class="content-card">
                            <h5 class="mb-3">Riset</h5>
                            <?php if (!empty($riset_list)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 60px;">No</th>
                                                <th>Judul</th>
                                                <th style="width: 100px;">Tahun</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($riset_list as $ris): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= e($ris['judul']) ?></td>
                                                    <td><?= e($ris['tahun']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-message">
                                    <i class="fas fa-flask"></i>
                                    <p>Belum ada riset</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- KEKAYAAN INTELEKTUAL -->
                    <div class="tab-pane fade" id="ki">
                        <div class="content-card">
                            <h5 class="mb-3">Kekayaan Intelektual</h5>
                            <?php if (!empty($ki_list)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 60px;">No</th>
                                                <th>Judul</th>
                                                <th style="width: 100px;">Tahun</th>
                                                <th style="width: 180px;">No. Permohonan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($ki_list as $ki): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= e($ki['judul']) ?></td>
                                                    <td><?= e($ki['tahun']) ?></td>
                                                    <td><?= e($ki['nomor']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-message">
                                    <i class="fas fa-lightbulb"></i>
                                    <p>Belum ada kekayaan intelektual</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- PPM -->
                    <div class="tab-pane fade" id="ppm">
                        <div class="content-card">
                            <h5 class="mb-3">PPM (Pengabdian Pada Masyarakat)</h5>
                            <?php if (!empty($ppm_list)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 60px;">No</th>
                                                <th>Judul</th>
                                                <th style="width: 100px;">Tahun</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($ppm_list as $ppm): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= e($ppm['judul']) ?></td>
                                                    <td><?= e($ppm['tahun']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-message">
                                    <i class="fas fa-hands-helping"></i>
                                    <p>Belum ada PPM</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

            <?php else: ?>

                <!-- PROFILE CARD MAHASISWA -->
                <div class="profile-card">
                    <img src="<?= file_exists($img_base_mhs . $mhs['foto']) ?
                                    $img_base_mhs . e($mhs['foto']) :
                                    $img_base_mhs . 'default.jpg' ?>"
                        class="profile-photo"
                        alt="<?= e($mhs['nama']) ?>">

                    <div class="profile-info">
                        <h2><?= e($mhs['nama']) ?></h2>
                        <div class="role"><?= e($mhs['role']) ?></div>

                        <?php if (!empty($mhs['jurusan'])): ?>
                            <div class="expertise">
                                Jurusan <?= e($mhs['jurusan']) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($mhs['program_studi'])): ?>
                            <div class="expertise">
                                Prodi <?= e($mhs['program_studi']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endif; ?>

        </div>

    </div>

    <!-- koneksi footer -->
    <?php include __DIR__ . '/../template/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
pg_close($conn);
?>