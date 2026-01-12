<?php
// navbar.php
// Pastikan file ini di-include di setiap halaman sebelum HTML navbar
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- NAVBAR CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style_dropdown.css">
<style>
  .navbar-top {
    background: #0b3b63;
    height: 40px;
  }

  .navbar-main {
    background: #fff;
  }

  .navbar-main .nav-link {
    color: #0b3b63;
    font-weight: 600;
    margin: 0.8px;
  }

  .navbar-main .nav-link:hover {
    color: #f37021;
  }

  .nav-item {
    position: relative;
  }

  .nav-item::after {
    content: '';
    position: absolute;
    bottom: 0;
    width: 0%;
    left: 50%;
    height: 2px;
    background-color: #f37021;
    transition: width 0.3s;
    transform: translateX(-50%);
  }

  .nav-item:hover::after {
    width: 100%;
  }

  #navMenu .navbar-nav {
    gap: 15px;
  }

  /* Navbar link aktif */
  .navbar-main .nav-link.active {
    color: #f37021 !important;
    font-weight: 600;
  }

  .nav-item.active::after {
    width: 100%;
    background-color: #f37021;
  }


  
</style>

<!-- NAVBAR HTML -->
<div class="navbar-top"></div>
<nav class="navbar navbar-expand-lg navbar-main">
  <div class="container">
    <a class="navbar-brand" href="../index.php">
      <img src="../../dashboard_admin/assets/img/logo.png" alt="Applied Informatics" height="50">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-center">
        <!-- Menu Utama -->
        <li class="nav-item <?= ($current_page == 'index.php') ? 'active' : '' ?>">
          <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>" href="../index.php">Beranda</a>
        </li>
        <li class="nav-item <?= ($current_page == 'products.php') ? 'active' : '' ?>">
          <a class="nav-link <?= ($current_page == 'products.php') ? 'active' : '' ?>" href="../view/products.php">Produk</a>
        </li>
        <li class="nav-item <?= ($current_page == 'members.php') ? 'active' : '' ?>">
          <a class="nav-link <?= ($current_page == 'members.php') ? 'active' : '' ?>" href="../view/members.php">Anggota</a>
        </li>
        <li class="nav-item <?= ($current_page == 'partners.php') ? 'active' : '' ?>">
          <a class="nav-link <?= ($current_page == 'partners.php') ? 'active' : '' ?>" href="../view/partners.php">Mitra</a>
        </li>
        <li class="nav-item <?= ($current_page == 'news.php') ? 'active' : '' ?>">
          <a class="nav-link <?= ($current_page == 'news.php') ? 'active' : '' ?>" href="../view/news.php">Berita</a>
        </li>

        <!-- Dropdown -->
        <li class="nav-item dropdown <?= in_array($current_page, ['peminjaman.php', 'daftarAsisten.php', 'daftarMagang.php']) ? 'active' : '' ?>">
          <a class="nav-link dropdown-toggle <?= in_array($current_page, ['peminjaman.php', 'daftarAsisten.php', 'daftarMagang.php']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
            Layanan
          </a>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item <?= ($current_page == 'peminjaman.php') ? 'active' : '' ?>" href="../view/peminjaman.php">Peminjaman Ruangan</a>
            </li>
            <li>
              <a class="dropdown-item <?= ($current_page == 'daftarAsisten.php') ? 'active' : '' ?>" href="../view/daftarAsisten.php">Daftar Asisten</a>
            </li>
            <li>
              <a class="dropdown-item <?= ($current_page == 'daftarMagang.php') ? 'active' : '' ?>" href="../view/daftarMagang.php">Daftar Magang</a>
            </li>
          </ul>
        </li>

        <!-- Kontak -->
        <li class="nav-item <?= ($current_page == 'kontak.php') ? 'active' : '' ?>">
          <a class="nav-link <?= ($current_page == 'kontak.php') ? 'active' : '' ?>" href="../view/kontak.php">Kontak</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
</head>