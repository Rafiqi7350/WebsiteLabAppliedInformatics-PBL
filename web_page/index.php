<?php
include '../dashboard_admin/model/config_db.php';
$current_page = basename($_SERVER['PHP_SELF']);
$news = pg_query($conn, "SELECT * FROM news ORDER BY id DESC LIMIT 4");
$gallery = pg_query($conn, "SELECT * FROM gallery ORDER BY id DESC LIMIT 6");
$profile = pg_query($conn, "SELECT * FROM profile_lab LIMIT 1");
$p = pg_fetch_assoc($profile);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lab Applied Informatics - Politeknik Negeri Malang</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <link rel="stylesheet" href="./template/style_beranda.css">
      <link rel="stylesheet" href="./template/style_dropdown.css?v=1.0">


</head>

<body>
  <!-- Top Navbar Bar -->
  <div class="navbar-top"></div>

  <!-- Main Navigation -->
  <nav class="navbar navbar-expand-lg navbar-main sticky-top">
    <div class="container">
      <a class="navbar-brand" href="./index.php">
        <img src="../dashboard_admin/assets/img/logo.png" alt="Applied Informatics" height="50">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item <?= ($current_page == 'index.php') ? 'active' : '' ?>">
            <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>" href="index.php">Beranda</a>
          </li>
          <li class="nav-item <?= ($current_page == 'products.php') ? 'active' : '' ?>">
            <a class="nav-link" href="../web_page/view/products.php">Produk</a>
          </li>
          <li class="nav-item <?= ($current_page == 'members.php') ? 'active' : '' ?>">
            <a class="nav-link" href="../web_page/view/members.php">Anggota</a>
          </li>
          <li class="nav-item <?= ($current_page == 'partners.php') ? 'active' : '' ?>">
            <a class="nav-link" href="../web_page/view/partners.php">Mitra</a>
          </li>
          <li class="nav-item <?= ($current_page == 'news.php') ? 'active' : '' ?>">
            <a class="nav-link" href="../web_page/view/news.php">Berita</a>
          </li>

          <li class="nav-item dropdown <?= in_array($current_page, ['peminjaman.php', 'daftarAsisten.php', 'daftarMagang.php']) ? 'active' : '' ?>">
            <a class="nav-link dropdown-toggle <?= in_array($current_page, ['peminjaman.php', 'daftarAsisten.php', 'daftarMagang.php']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
              Layanan
            </a>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item <?= ($current_page == 'peminjaman.php') ? 'active' : '' ?>" href="./view/peminjaman.php">Peminjaman Ruangan</a>
              </li>
              <li>
                <a class="dropdown-item <?= ($current_page == 'daftarAsisten.php') ? 'active' : '' ?>" href="./view/daftarAsisten.php">Daftar Asisten</a>
              </li>
              <li>
                <a class="dropdown-item <?= ($current_page == 'daftarMagang.php') ? 'active' : '' ?>" href="./view/daftarMagang.php">Daftar Magang</a>
              </li>
            </ul>
          </li>

          <li class="nav-item <?= ($current_page == 'kontak.php') ? 'active' : '' ?>">
            <a class="nav-link <?= ($current_page == 'kontak.php') ? 'active' : '' ?>"
              href="../web_page/view/kontak.php">Kontak</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero mb-5">
    <div class="container">
      <div class="hero-content text-start p-0">
        <h1 class="hero-title">Laboratorium Informatika Terapan</h1>
        <p class="hero-sub">Politeknik Negeri Malang</p>
      </div>
    </div>
  </section>

  <!-- History Section -->
  <section class="container my-5">
    <div class="history-box">
      <h2 class="section">TENTANG LABORATORIUM INFORMATIKA TERAPAN</h2>
      <p class="history-text">
        <?= nl2br(htmlspecialchars($p['deskripsi'] ?? 'Belum ada deskripsi yang ditambahkan.')); ?>
      </p>
    </div>
  </section>

  <!-- Campus News Section -->
  <section class="container my-5">
    <div class="section-title-wrap news">
      <h3 class="section-title">BERITA KAMPUS</h3>
    </div>

    <div class="row g-4 justify-content-center">
      <?php
      $news_count = 0;
      while ($n = pg_fetch_assoc($news)) {
        $news_count++;
      ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="card news-card shadow-sm h-100">

            <img src="../dashboard_admin/assets/img/news/<?php echo htmlspecialchars($n['gambar']); ?>"
              class="card-img-top"
              alt="<?php echo htmlspecialchars($n['judul']); ?>">

            <div class="card-body d-flex flex-column">

              <div>
                <h6 class="fw-bold"><?php echo htmlspecialchars($n['judul']); ?></h6>
                <p class="small text-muted">
                  <?php echo htmlspecialchars(substr($n['isi'], 0, 100)); ?>...
                </p>
              </div>

              <!-- FIX: Tombol menuju link berita dari admin -->
              <a href="<?= htmlspecialchars($n['link']); ?>"
                class="btn btn-read mt-auto"
                target="_blank">
                Baca Selengkapnya
              </a>

            </div>
          </div>
        </div>
      <?php } ?>

      <?php if ($news_count === 0): ?>
        <p class="text-center text-muted">Belum ada berita yang ditambahkan.</p>
      <?php endif; ?>
    </div>

    <div class="text-center mt-5">
      <a href="../web_page/view/news.php" class="btn btn-read">Lihat Semua</a>
    </div>
  </section>


  <!-- Priority Research Topics Section -->
  <section class="container my-4">
    <div class="section-title-wrap">
      <h3 class="section-title">TOPIK UTAMA PENELITIAN</h3>
    </div>

    <?php

    $topik_query = pg_query($conn, "SELECT * FROM topik_riset ORDER BY id ASC");
    ?>

    <div class="row g-4 mt-3">
      <?php
      $ada_data = false;
      $counter = 1; // Inisialisasi counter untuk nomor urut
      while ($t = pg_fetch_assoc($topik_query)) :
        $ada_data = true;
      ?>
        <div class="col-sm-6 col-lg-4">
          <div class="topik-diagram-box h-100 p-4">

            <div class="topik-number-circle">
              <?= $counter ?>
            </div>

            <h6 class="mt-2 mb-2 fw-bold text-dark">
              <?= htmlspecialchars($t['judul']) ?>
            </h6>
            <p class="text-muted mb-0 small">
              <?= htmlspecialchars($t['deskripsi']) ?>
            </p>
          </div>
        </div>
      <?php
        $counter++; // Tambah counter
      endwhile; ?>

      <?php if (!$ada_data) : ?>
        <p class="text-center text-muted">Belum ada topik riset yang ditambahkan.</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- Vision & Mission Section -->
  <section class="container my-5">
    <div class="section-title-wrap visimisi">
      <h3 class="section-title">VISI & MISI</h3>
    </div>
    <div class="row g-4">
      <!-- Vision & Mission Box -->
      <div class="col-lg-8">
        <div class="visi-misi-box h-100">
          <h5 class="fw-bold">VISI</h5>
          <p><?= nl2br(htmlspecialchars($p['visi'] ?? 'Visi belum ditambahkan.')); ?></p>

          <h5 class="fw-bold mt-4">MISI</h5>
          <ul>
            <?php
            $misiArray = json_decode($p['misi'] ?? '[]', true);
            if (is_array($misiArray) && !empty($misiArray)) {
              foreach ($misiArray as $misi) {
                echo "<li>" . htmlspecialchars($misi) . "</li>";
              }
            } elseif (!empty($p['misi'])) {
              echo "<li>" . nl2br(htmlspecialchars($p['misi'])) . "</li>";
            } else {
              echo "<li>Misi belum ditambahkan.</li>";
            }
            ?>
          </ul>
        </div>
      </div>

      <!-- Kepala Lab Card -->
      <div class="col-lg-4">
        <?php
        $kepala_query = pg_query($conn, "SELECT * FROM member_dosen WHERE LOWER(role) = 'kepala lab' LIMIT 1");
        $kepalalab = pg_fetch_assoc($kepala_query);
        ?>

        <div class="card text-center shadow-sm p-4 h-100 kepala-lab-card">
          <?php if (!empty($kepalalab)) : ?>
            <?php
            $foto = "../dashboard_admin/assets/img/members/dosen/" . ($kepalalab['foto'] ?? '');
            if (empty($kepalalab['foto']) || !file_exists($foto)) {
              $foto = "../assets/img/member_dosen/kepala_lab.png";
            }
            ?>

            <img src="<?= htmlspecialchars($foto); ?>"
              class="rounded-circle mx-auto mb-3"
              width="120" height="120"
              style="object-fit: cover;"
              alt="<?= htmlspecialchars($kepalalab['nama']); ?>">

            <h6 class="fw-bold mb-1"><?= htmlspecialchars(strtoupper($kepalalab['role'])); ?></h6>
            <p class="mb-1 fw-bold text-dark"><?= htmlspecialchars($kepalalab['nama']); ?></p>

            <?php if (!empty($kepalalab['expertise'])) : ?>
              <p class="text-muted small mb-3">
                <?= htmlspecialchars($kepalalab['expertise']); ?>
              </p>
            <?php endif; ?>

            <a href="../web_page/view/members.php?id=<?= htmlspecialchars($kepalalab['id']); ?>"
              class="btn btn-read mx-auto">
              More Detail
            </a>

          <?php else : ?>
            <img src="../assets/img/member_dosen/kepala_lab.png"
              class="rounded-circle mx-auto mb-3"
              width="120" style="opacity: 0.7;"
              alt="Kepala Lab">

            <h6 class="mt-3 fw-bold">KEPALA LAB</h6>
            <p class="text-muted"><i>Belum ada Kepala Lab di database.</i></p>

            <a href="../web_page/view/members.php"
              class="btn btn-outline-primary btn-sm mt-3 mx-auto">
              Lihat Anggota
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- Gallery Section -->
  <section class="container my-4">
    <div class="section-title-wrap">
      <h3 class="section-title">GALLERY</h3>
    </div>

    <?php
    // Ambil ulang data karena pointer mungkin sudah habis
    $gallery_query_slide = pg_query($conn, "SELECT * FROM gallery ORDER BY id DESC LIMIT 6");
    $all_gallery = pg_fetch_all($gallery_query_slide);
    $gallery_count = count($all_gallery);
    ?>

    <?php if ($gallery_count > 0) : ?>
      <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-indicators">
          <?php for ($i = 0; $i < $gallery_count; $i++): ?>
            <button type="button"
              data-bs-target="#galleryCarousel"
              data-bs-slide-to="<?= $i ?>"
              class="<?= $i === 0 ? 'active' : '' ?>"
              aria-current="<?= $i === 0 ? 'true' : 'false' ?>"
              aria-label="Slide <?= $i + 1 ?>">
            </button>
          <?php endfor; ?>
        </div>

        <div class="carousel-inner rounded-3 shadow-lg">
          <?php $i = 0;
          foreach ($all_gallery as $g) : ?>
            <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
              <img src="../dashboard_admin/assets/img/gallery/<?php echo htmlspecialchars($g['gambar']); ?>"
                class="d-block"
                alt="Galeri Lab - <?= $i + 1 ?>">
              <?php if (!empty($g['caption'])): ?>
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3">
                  <h5><?= htmlspecialchars($g['caption']) ?></h5>
                </div>
              <?php endif; ?>
            </div>
          <?php $i++;
          endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>

      </div> <?php else: ?>
      <p class="text-center text-muted mt-3">Belum ada gambar di galeri.</p>
    <?php endif; ?>
    <?php
    ?>
  </section>

  <!-- Footer -->
  <footer class="bg-primary text-white mt-5 pt-5 pb-3" style="background-color:#053C6D !important;">
    <div class="container">

      <div class="row">

        <!-- Kolom Kiri -->
        <div class="col-md-8 mb-4">
          <!-- Logo -->
          <img src="./../dashboard_admin/assets/img/logo-putih.png" alt="Logo" class="mb-3" style="height: 55px;">

          <!-- Deskripsi -->
          <p class="mb-2">
            Applied Informatics Laboratory<br>
            Postgraduate Building, 2nd Floor, Malang State Polytechnic
          </p>

          <!-- Social Media Icons -->
          <div class="d-flex gap-3 fs-3 mt-3">
            <a href="#" class="text-white"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-white"><i class="bi bi-twitter"></i></a>
            <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-white"><i class="bi bi-github"></i></a>
          </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-4 mb-4">
          <h5 class="fw-bold">Quick Link</h5>
          <ul class="list-unstyled mt-3">
            <li class="mb-2"><a href="./index.php" class="text-white text-decoration-none">Beranda</a></li>
            <li class="mb-2"><a href="./view/products.php" class="text-white text-decoration-none">Produk</a></li>
            <li class="mb-2"><a href="./view/members.php" class="text-white text-decoration-none">Anggota</a></li>
            <li class="mb-2"><a href="./view/mitra.php" class="text-white text-decoration-none">Mitra</a></li>
            <li class="mb-2"><a href="./view/news.php" class="text-white text-decoration-none">Berita</a></li>
            <li class="mb-2"><a href="./view/peminjaman.php" class="text-white text-decoration-none">Peminjaman</a></li>
            <li class="mb-2"><a href="./view/kontak.php" class="text-white text-decoration-none">Kontak</a></li>
          </ul>
        </div>

      </div>

      <hr class="border-light">

      <!-- Copyright -->
      <div class="text-side pt-2">
        Copyright © 2025 Lab Applied Informatics Polinema
      </div>

    </div>
  </footer>

  <!-- Bootstrap Icons (untuk ikon sosmed) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>