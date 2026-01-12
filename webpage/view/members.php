<?php
// Koneksi ke database
include '../../dashboard_admin/model/config_db.php';

// Ambil Kepala Lab langsung dari tabel Members_Dosen
$kepala_lab_query = "SELECT * FROM member_dosen WHERE role = 'Kepala Lab' LIMIT 1";
$kepala_lab_result = pg_query($conn, $kepala_lab_query);

if (!$kepala_lab_result) {
    // Penanganan error jika query gagal
    die("Query Kepala Lab Error: " . pg_last_error($conn));
}

// Ambil data Kepala Lab sebagai associative array
$kepala_lab = pg_fetch_assoc($kepala_lab_result);

// Ambil Semua Dosen (kecuali kepala lab)
$dosen_query = "SELECT * FROM member_dosen WHERE role != 'Kepala Lab' ORDER BY nama ASC";
$dosen_result = pg_query($conn, $dosen_query);

// Ambil Semua Mahasiswa
$mahasiswa_query = "SELECT * FROM member_mahasiswa ORDER BY nama ASC";
$mahasiswa_result = pg_query($conn, $mahasiswa_query);
?>

<?php include __DIR__ . '/../template/navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members - Lab Applied Informatics</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../template/style_member.css?=v1.0">
    <link rel="stylesheet" href="../template/style_dropdown.css?v=1.0">

</head>

<body>

    <div class="hero-members">
        <h1>ANGGOTA TIM KAMI</h1>
    </div>


    <div class="members-container">

        <div class="tab-nav">
            <button class="active" onclick="showTab('kepala-lab')">Kepala Lab</button>
            <button onclick="showTab('dosen')">Dosen</button>
            <button onclick="showTab('mahasiswa')">Mahasiswa</button>
        </div>

        <div id="kepala-lab" class="tab-content-section active">
            <?php if ($kepala_lab): // Cek apakah data kepala lab ada ?>
                <div class="kepala-lab-grid">
                    <div class="kepala-card">
                        <img src="../../dashboard_admin/assets/img/members/dosen/<?= htmlspecialchars($kepala_lab['foto'] ?? 'default.jpg'); ?>"
                            alt="<?= htmlspecialchars($kepala_lab['nama']); ?>">

                        <div class="kepala-card-content">
                            <h3><?= htmlspecialchars($kepala_lab['nama']); ?></h3>

                            <div class="role">Head of Laboratory</div>

                            <div class="description">
                                <?php
                                // Tampilkan deskripsi/expertise, dengan fallback default jika kosong
                                if (!empty($kepala_lab['expertise'])) {
                                    echo htmlspecialchars($kepala_lab['expertise']);
                                } elseif (!empty($kepala_lab['deskripsi'])) {
                                    echo htmlspecialchars(substr($kepala_lab['deskripsi'], 0, 200));
                                    if (strlen($kepala_lab['deskripsi']) > 200) echo '...';
                                } else {
                                    echo 'Software Engineering, Geographic Information System, Spatial Data, Educational Technology, Software Testing, Computer Networks, Geographic Information System';
                                }
                                ?>
                            </div>

                            <a href="detail-member.php?id=<?= $kepala_lab['id']; ?>&type=dosen" class="btn-detail">
                                More Detail
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: // Tampilkan pesan jika data Kepala Lab tidak ada ?>
                <div class="empty-state">
                    <i class="fas fa-user-tie"></i>
                    <p>Belum ada data Kepala Lab.</p>
                </div>
            <?php endif; ?>
        </div>

        <div id="dosen" class="tab-content-section">
            <?php if (pg_num_rows($dosen_result) > 0): // Cek apakah ada data dosen selain Kepala Lab ?>
                <div class="members-grid">
                    <?php while ($dosen = pg_fetch_assoc($dosen_result)): // Loop untuk menampilkan setiap dosen ?>
                        <div class="member-card">
                            <img src="../../dashboard_admin/assets/img/members/dosen/<?= htmlspecialchars($dosen['foto'] ?? 'default.jpg'); ?>"
                                alt="<?= htmlspecialchars($dosen['nama']); ?>">

                            <h3><?= htmlspecialchars($dosen['nama']); ?></h3>

                            <div class="role dosen">
                                <?= htmlspecialchars($dosen['role'] ?? 'Dosen'); ?>
                            </div>

                            <div class="description">
                                <?php
                                // Tampilkan deskripsi/expertise Dosen
                                if (!empty($dosen['expertise'])) {
                                    echo htmlspecialchars(substr($dosen['expertise'], 0, 100));
                                    if (strlen($dosen['expertise']) > 100) echo '...';
                                } elseif (!empty($dosen['deskripsi'])) {
                                    echo htmlspecialchars(substr($dosen['deskripsi'], 0, 100));
                                    if (strlen($dosen['deskripsi']) > 100) echo '...';
                                } else {
                                    echo 'Dosen Laboratorium Applied Informatics';
                                }
                                ?>
                            </div>

                            <a href="detail-member.php?id=<?= $dosen['id']; ?>&type=dosen" class="btn-detail">
                                More Detail
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: // Tampilkan pesan jika data Dosen tidak ada ?>
                <div class="empty-state">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <p>Belum ada data dosen.</p>
                </div>
            <?php endif; ?>
        </div>

        <div id="mahasiswa" class="tab-content-section">
            <?php if (pg_num_rows($mahasiswa_result) > 0): // Cek apakah ada data mahasiswa ?>
                <div class="members-grid">
                    <?php while ($mhs = pg_fetch_assoc($mahasiswa_result)): // Loop untuk menampilkan setiap mahasiswa ?>
                        <div class="member-card">
                            <img src="../../dashboard_admin/assets/img/members/mahasiswa/<?= htmlspecialchars($mhs['foto'] ?? 'default.jpg'); ?>"
                                alt="<?= htmlspecialchars($mhs['nama']); ?>">

                            <h3><?= htmlspecialchars($mhs['nama']); ?></h3>

                            <div class="role mahasiswa">
                                <?= htmlspecialchars($mhs['role'] ?? 'Asisten Lab'); ?>
                            </div>

                            <div class="info-detail">
                                <?php if (!empty($mhs['jurusan'])): ?>
                                    <div>Jurusan <?= htmlspecialchars($mhs['jurusan']); ?></div>
                                <?php endif; ?>

                                <?php if (!empty($mhs['program_studi'])): ?>
                                    <div>Prodi <?= htmlspecialchars($mhs['program_studi']); ?></div>
                                <?php endif; ?>

                                <?php if (empty($mhs['jurusan']) && empty($mhs['program_studi'])): ?>
                                    <div>Mahasiswa Asisten Laboratorium Applied Informatics</div>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: // Tampilkan pesan jika data Mahasiswa tidak ada ?>
                <div class="empty-state">
                    <i class="fas fa-user-graduate"></i>
                    <p>Belum ada data mahasiswa.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <script>
        function showTab(tabId) {
            // Sembunyikan semua konten tab
            var contents = document.getElementsByClassName('tab-content-section');
            for (var i = 0; i < contents.length; i++) {
                contents[i].classList.remove('active');
            }

            // Hapus kelas 'active' dari semua tombol navigasi
            var buttons = document.querySelectorAll('.tab-nav button');
            buttons.forEach(function(btn) {
                btn.classList.remove('active');
            });

            // Tampilkan tab yang dipilih
            document.getElementById(tabId).classList.add('active');

            // Tambahkan kelas 'active' pada tombol yang diklik
            event.target.classList.add('active');
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php include __DIR__ . '/../template/footer.php'; ?>

</body>

</html>

<?php
// Menutup koneksi database
pg_close($conn);
?>