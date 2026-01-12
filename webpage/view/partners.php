<?php
// Mengimpor file konfigurasi database dan model partner
include '../../dashboard_admin/model/config_db.php';
include '../../dashboard_admin/model/model_partner.php';

// Mapping kategori mitra ke nama folder penyimpanan logo
$folderMap = [
    "Industry Partner"           => "industry_partner",
    "Educational Institutions"   => "educational_institutions",
    "Governement Institutions" => "government_institutions",
    "International Institutions" => "international_institutions"
];

// Mengambil data partner dari database berdasarkan kategori
$industry       = getPartnersByCategory($conn, 'Industry Partner');
$education      = getPartnersByCategory($conn, 'Educational Institutions');
$government     = getPartnersByCategory($conn, 'Governement Institutions');
$international  = getPartnersByCategory($conn, 'International Institutions');

// Fungsi untuk membentuk path logo yang aman dan memverifikasi keberadaan file
function getLogoPath($folderMap, $kategori, $logo)
{
    // Tentukan folder berdasarkan kategori, default ke 'others'
    $folder = $folderMap[$kategori] ?? 'others';
    // Bentuk path file logo yang diharapkan
    $path = "../../dashboard_admin/assets/img/partners/$folder/$logo";

    // Periksa apakah file ada dan nama logo tidak kosong
    if (file_exists($path) && !empty($logo)) {
        return $path; // Kembalikan path logo yang valid
    }
    // Jika file tidak ada atau nama logo kosong, kembalikan path ke gambar default (no-image)
    return "../../dashboard_admin/assets/img/no-image.png";
}

// Fungsi untuk menghitung jumlah baris/partner dari hasil query database (PostgreSQL)
function countPartners($result)
{
    if (!$result) return 0; // Jika hasil query gagal, kembalikan 0
    $count = pg_num_rows($result); // Hitung jumlah baris
    pg_result_seek($result, 0); // Reset pointer hasil query agar bisa di-fetch lagi di bagian tampilan (HTML)
    return $count;
}

// Hitung jumlah partner untuk setiap kategori
$industryCount = countPartners($industry);
$educationCount = countPartners($education);
$governmentCount = countPartners($government);
$internationalCount = countPartners($international);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitra Kami - Lab Applied Informatics</title>

</head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="../template/style_partner.css">
<link rel="stylesheet" href="../template/style_dropdown.css">

<body>
    <!-- navbar -->
    <?php include __DIR__ . '/../template/navbar.php'; ?>

    <section class="partners-header">
        <div class="container content">
            <h1>Mitra Kami</h1>
        </div>
    </section>

    <section class="description-box-wrapper">
        <div class="container">
            <div class="description-box fade-in">
                <p>Program Pascasarjana Teknologi Informasi di Politeknik Negeri Malang berkolaborasi dengan berbagai mitra strategis, termasuk industri, akademisi, pemerintah, dan lembaga internasional untuk meningkatkan kualitas pembelajaran. Kolaborasi ini memberikan pengalaman belajar yang relevan dan aplikatif yang berorientasi pada kehidupan profesional dan standar global.</p>
            </div>
        </div>
    </section>

    <section class="container mitra-section">

        <div class="mitra-box fade-in">
            <?php if ($industryCount > 0): ?>
                <span class="stats-overlay"><?= $industryCount ?> Partners</span>
            <?php endif; ?>

            <h3>Industry Partners</h3>

            <?php if ($industryCount > 0): ?>
                <div class="logo-grid">
                    <?php while ($p = pg_fetch_assoc($industry)): ?>
                        <div class="logo-grid-item" title="<?= htmlspecialchars($p['nama']) ?>">
                            <img src="<?= getLogoPath($folderMap, 'Industry Partner', $p['logo']) ?>"
                                alt="<?= htmlspecialchars($p['nama']) ?>"
                                loading="lazy">
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-building"></i>
                    <p>No industry partners available yet</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="mitra-box fade-in">
            <?php if ($educationCount > 0): ?>
                <span class="stats-overlay"><?= $educationCount ?> Partners</span>
            <?php endif; ?>

            <h3>Educational Institutions</h3>

            <?php if ($educationCount > 0): ?>
                <div class="logo-grid">
                    <?php while ($p = pg_fetch_assoc($education)): ?>
                        <div class="logo-grid-item" title="<?= htmlspecialchars($p['nama']) ?>">
                            <img src="<?= getLogoPath($folderMap, 'Educational Institutions', $p['logo']) ?>"
                                alt="<?= htmlspecialchars($p['nama']) ?>"
                                loading="lazy">
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-graduation-cap"></i>
                    <p>No educational partners available yet</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="mitra-box fade-in">
            <?php if ($governmentCount > 0): ?>
                <span class="stats-overlay"><?= $governmentCount ?> Partners</span>
            <?php endif; ?>

            <h3>Government Institutions</h3>

            <?php if ($governmentCount > 0): ?>
                <div class="logo-grid">
                    <?php while ($p = pg_fetch_assoc($government)): ?>
                        <div class="logo-grid-item" title="<?= htmlspecialchars($p['nama']) ?>">
                            <img src="<?= getLogoPath($folderMap, 'Governement Institutions', $p['logo']) ?>"
                                alt="<?= htmlspecialchars($p['nama']) ?>"
                                loading="lazy">
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-landmark"></i>
                    <p>No government partners available yet</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="mitra-box fade-in">
            <?php if ($internationalCount > 0): ?>
                <span class="stats-overlay"><?= $internationalCount ?> Partners</span>
            <?php endif; ?>

            <h3>International Institutions</h3>

            <?php if ($internationalCount > 0): ?>
                <div class="logo-grid international-logo">
                    <?php while ($p = pg_fetch_assoc($international)): ?>
                        <div class="logo-grid-item" title="<?= htmlspecialchars($p['nama']) ?>">
                            <img src="<?= getLogoPath($folderMap, 'International Institutions', $p['logo']) ?>"
                                alt="<?= htmlspecialchars($p['nama']) ?>"
                                loading="lazy">
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-globe"></i>
                    <p>No international partners available yet</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-5">
            <div class="partnership-cta fade-in">
                <h4>Interested in Partnering with Us?</h4>
                <p>We're always looking for new collaborations to enhance our programs and research initiatives.</p>
                <a href="../view/kontak.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-handshake me-2"></i>Contact Us
                </a>
            </div>
        </div>

    </section>

    <?php include __DIR__ . '/../template/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Opsi untuk Intersection Observer
            const observerOptions = {
                threshold: 0.1, // Mulai animasi ketika 10% elemen terlihat
                rootMargin: '0px 0px -50px 0px' // Margin untuk memicu observer lebih awal/lambat
            };

            // Inisialisasi Intersection Observer
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Tambahkan kelas 'visible' untuk memicu transisi/animasi CSS
                        entry.target.classList.add('visible');
                        // Hentikan pengamatan setelah elemen terlihat
                        // observer.unobserve(entry.target); 
                    }
                });
            }, observerOptions);

            // Amati semua elemen dengan kelas 'fade-in'
            document.querySelectorAll('.fade-in').forEach(el => {
                observer.observe(el);
            });

            // Tambahkan efek/feedback hover opsional pada item logo
            document.querySelectorAll('.logo-grid-item').forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)'; // Contoh transisi
                });
            });

            // Fallback untuk lazy loading gambar di browser lama
            if ('loading' in HTMLImageElement.prototype) {
                console.log('Native lazy loading supported');
            } else {
                // Jika lazy loading native tidak didukung, force load gambar
                const images = document.querySelectorAll('img[loading="lazy"]');
                images.forEach(img => {
                    img.src = img.src;
                });
            }
        });
    </script>

</body>

</html>