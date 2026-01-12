<?php
// Menginclude file konfigurasi database.
include '../../dashboard_admin/model/config_db.php';

// Mengambil semua data berita dari tabel 'news' dan mengurutkannya berdasarkan 'id' secara descending (terbaru di atas).
$result = pg_query($conn, "SELECT * FROM news ORDER BY id DESC");
?>

<!-- navbar -->
<?php include __DIR__ . '/../template/navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita - Lab AI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="../template/style_news.css?=v1.0">
<link rel="stylesheet" href="../template/style_dropdown.css?v=1.0">


<header class="news-header">
    <h1>BERITA TERKINI</h1>
</header>

<div class="title-section">Berita Terbaru Laboratorium Informatika Terapan</div>

<div class="grid-news">
    <?php 
    // Melakukan loop untuk menampilkan setiap baris berita yang diambil dari database.
    while ($row = pg_fetch_assoc($result)) { 
    ?>
        <div class="card">
            <img src="../../dashboard_admin/assets/img/news/<?= $row['gambar']; ?>">
            <div class="date"><?= $row['tanggal']; ?> - Berita Terkini</div>
            <div class="title"><?= $row['judul']; ?></div>
            <div class="desc"><?= substr($row['isi'], 0, 110); ?>...</div>
            <a href="<?= $row['link']; ?>" class="btn-read" target="_blank">Baca Selengkapnya</a>

        </div>
    <?php } ?>
</div>

<?php include __DIR__ . '/../template/footer.php'; ?>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Mendapatkan semua elemen dengan kelas 'card'.
        const cards = document.querySelectorAll(".card");

        // Membuat instance baru dari Intersection Observer.
        const observer = new IntersectionObserver(
            (entries) => {
                // Iterasi melalui setiap entri yang diamati.
                entries.forEach(entry => {
                    // Cek jika elemen sedang berada dalam viewport (intersecting).
                    if (entry.isIntersecting) {
                        // Tambahkan kelas 'show' untuk memicu animasi.
                        entry.target.classList.add("show");
                        // Hentikan pengamatan setelah animasi dipicu sekali.
                        observer.unobserve(entry.target); 
                    }
                });
            }, {
                // Threshold 0.2 berarti elemen dianggap intersecting ketika 20% dari elemen terlihat.
                threshold: 0.2
            }
        );

        // Mengamati setiap card.
        cards.forEach(card => observer.observe(card));
    });
</script>

</body>

</html>