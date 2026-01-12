<?php
include '../../dashboard_admin/model/config_db.php';
include '../../dashboard_admin/model/model_dashboard.php';
include '../model/middleware.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Lab AI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="../template/style_sidebar.css">
    <link rel="stylesheet" href="../template/style_dashboard.css?=v1.0">

    <style>

    </style>
</head>

<body>

    <?php include __DIR__ . '/../template/sidebar.php'; ?>
    <?php include __DIR__ . '/../template/topbar.php'; ?>

    <!-- HEADER -->
    <div class="dashboard-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="welcome-section">
                        <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
                        <p>Selamat datang di Lab AI - Monitoring & Analytics</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="header-stats">
                        <div class="header-stat-item">
                            <div class="label">Hari Ini</div>
                            <div class="value"><?= date('d M') ?></div>
                        </div>
                        <div class="header-stat-item">
                            <div class="label">Status</div>
                            <div class="value"><i class="bi bi-check-circle-fill"></i> Online</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="content" class="content">


        <!-- CONTENT WRAPPER -->
        <div class="content-wrapper">

            <!-- TITLE -->
            <div class="section-title">
                <i class="bi bi-bar-chart-fill"></i>
                Statistik Visual & Keseluruhan
            </div>

            <div class="visual-stats-wrapper">

                <!--  PIE CHART     -->
                <div>
                    <div class="card shadow-sm p-3">
                        <h6 class="mb-3">Anggota Lab</h6>
                        <canvas id="mainPieChart" height="240"></canvas>
                    </div>
                </div>

                <!-- STAT CARDS -->
                <div class="stats-grid-right">

                    <div class="stat-card blue">
                        <div class="stat-card-header">
                            <div class="stat-icon"><i class="bi bi-boxes"></i></div>
                        </div>
                        <div class="stat-card-body">
                            <div class="stat-number"><?= $totalProducts['total_products'] ?? 0; ?></div>
                            <div class="stat-label">Total Produk</div>
                        </div>
                        <div class="stat-footer"><i class="bi bi-clock"></i> Update terakhir</div>
                    </div>

                    <div class="stat-card green">
                        <div class="stat-card-header">
                            <div class="stat-icon"><i class="bi bi-images"></i></div>
                        </div>
                        <div class="stat-card-body">
                            <div class="stat-number"><?= $totalGallery['total_gallery'] ?? 0; ?></div>
                            <div class="stat-label">Total Gallery</div>
                        </div>
                        <div class="stat-footer"><i class="bi bi-clock"></i> Update terakhir</div>
                    </div>

                    <div class="stat-card orange">
                        <div class="stat-card-header">
                            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                        </div>
                        <div class="stat-card-body">
                            <div class="stat-number"><?= $totalPartners['total_partners'] ?? 0; ?></div>
                            <div class="stat-label">Total Partners</div>
                        </div>
                        <div class="stat-footer"><i class="bi bi-clock"></i> Update terakhir</div>
                    </div>

                    <div class="stat-card red">
                        <div class="stat-card-header">
                            <div class="stat-icon"><i class="bi bi-door-open"></i></div>
                        </div>
                        <div class="stat-card-body">
                            <div class="stat-number"><?= $totalPeminjaman['total_peminjaman'] ?? 0; ?></div>
                            <div class="stat-label">Peminjaman Ruang</div>
                        </div>
                        <div class="stat-footer"><i class="bi bi-clock"></i> Update terakhir</div>
                    </div>
                </div>
            </div>



            <!--  AKSI CEPAT -->
            <div class="section-title">
                <i class="bi bi-lightning-fill"></i>
                Aksi Cepat
            </div>

            <div class="quick-actions">
                <div class="action-buttons">
                    <a href="../view/partners.php" class="action-btn">
                        <i class="bi bi-plus-circle-fill"></i>
                        Tambah Partner
                    </a>
                    <a href="../view/products.php" class="action-btn">
                        <i class="bi bi-plus-circle-fill"></i>
                        Tambah Produk
                    </a>
                    <a href="../view/news.php" class="action-btn">
                        <i class="bi bi-plus-circle-fill"></i>
                        Tambah Berita
                    </a>
                    <a href="#" class="action-btn">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        Lihat Statistik
                    </a>
                </div>
            </div>

            <!--   AKTIVITAS -->
            <div class="section-title">
                <i class="bi bi-clock-history"></i>
                Aktivitas Terbaru
            </div>

            <div class="activity-section">
                <div class="activity-card">
                    <ul class="activity-list">
                        <li class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Partner baru ditambahkan</div>
                                <div class="activity-time">2 jam yang lalu</div>
                            </div>
                        </li>

                        <li class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-box"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Produk baru ditambahkan</div>
                                <div class="activity-time">5 jam yang lalu</div>
                            </div>
                        </li>

                        <li class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-newspaper"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Berita baru dipublikasikan</div>
                                <div class="activity-time">1 hari yang lalu</div>
                            </div>
                        </li>

                        <li class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-briefcase"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Pendaftar magang baru</div>
                                <div class="activity-time">2 hari yang lalu</div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="activity-card">
                    <h6 class="mb-3" style="font-weight: 600; color: #2c3e50;">Ringkasan Sistem (Pending)</h6>
                    <ul class="activity-list">

                        <li class="activity-item">
                            <div class="activity-icon" style="background: #fff3e0;">
                                <i class="bi bi-person-plus" style="color: #fb8c00;"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Daftar Magang Pending</div>
                                <div class="activity-time"><?= $pendingMagang['pending_magang'] ?? 0 ?> pendaftar</div>
                            </div>
                        </li>

                        <li class="activity-item">
                            <div class="activity-icon" style="background: #e8f5e9;">
                                <i class="bi bi-person-badge-fill" style="color: #43a047;"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Rekrut Asisten Pending</div>
                                <div class="activity-time"><?= $pendingAsisten['pending_asisten'] ?? 0 ?> pendaftar</div>
                            </div>
                        </li>

                        <li class="activity-item">
                            <div class="activity-icon" style="background: #e3f2fd;">
                                <i class="bi bi-door-open-fill" style="color: #1e88e5;"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Peminjaman Ruang Pending</div>
                                <div class="activity-time"><?= $pendingPeminjaman['pending_peminjaman'] ?? 0 ?> permintaan</div>
                            </div>
                        </li>

                    </ul>
                </div>

            </div>

        </div>
    </div>


    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // PIE CHART
        document.addEventListener("DOMContentLoaded", function() {
            const ctx1 = document.getElementById('mainPieChart');

            new Chart(ctx1, {
                type: 'pie',
                data: {
                    labels: ['Magang', 'Asisten', 'Dosen', 'Mahasiswa'],
                    datasets: [{
                        data: [
                            <?= $totalMagang['total_magang'] ?? 0 ?>,
                            <?= $totalAsisten['total_asisten'] ?? 0 ?>,
                            <?= $totalMemberDosen['total_member_dosen'] ?? 0 ?>,
                            <?= $totalMemberMahasiswa['total_member_mahasiswa'] ?? 0 ?>,
                        ],
                        backgroundColor: ['#fb8c00', '#e53935', '#1e88e5', '#43a047']
                    }]
                }
            });
        });
    </script>

</body>

</html>