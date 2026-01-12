<?php 
$page = basename($_SERVER['PHP_SELF']); 
?>

<div id="sidebar" class="sidebar p-3">
    <div class="text-center mb-4">
        <a href="index.php"><img src="../assets/img/logo.png" id="logoSidebar"></a>
    </div>

    <ul class="nav flex-column">

        <li>
            <a href="info_profil.php" class="navlink <?= ($page == 'info_profil.php') ? 'active' : '' ?>">
                <i class="bi bi-person-badge"></i>
                <span>Info Profil</span>
            </a>
        </li>

        <li>
            <a href="products.php" class="navlink <?= ($page == 'products.php') ? 'active' : '' ?>">
                <i class="bi bi-box-seam"></i>
                <span>Produk</span>
            </a>
        </li>

        <?php 
        $membersActive = in_array($page, ['members_mahasiswa.php','members_dosen.php']);
        ?>
        <li>
            <a class="navlink dropdown-toggle <?= $membersActive ? 'active' : '' ?>" data-bs-toggle="collapse" href="#membersMenu">
                <i class="bi bi-people"></i>
                <span>Anggota</span>
            </a>
            <div class="collapse <?= $membersActive ? 'show' : '' ?>" id="membersMenu">
                <ul class="nav flex-column ms-3">
                    <li>
                        <a href="members_mahasiswa.php" class="navlink <?= ($page == 'members_mahasiswa.php') ? 'active' : '' ?>">
                            Anggota Mahasiswa
                        </a>
                    </li>
                    <li>
                        <a href="members_dosen.php" class="navlink <?= ($page == 'members_dosen.php') ? 'active' : '' ?>">
                            Anggota Dosen
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <?php 
        // Menggunakan nama file baru (karya) untuk menentukan aktivasi menu induk
        $uploadActive = in_array($page, [
            'publikasi_karya.php',
            'ppm_karya.php',
            'riset_karya.php',
            'kekayaan_intelektual_karya.php'
        ]);
        ?>
        <li>
            <a class="navlink dropdown-toggle <?= $uploadActive ? 'active' : '' ?>" data-bs-toggle="collapse" href="#uploadBerkasMenu">
                <i class="bi bi-upload"></i>
                <span>Upload Berkas</span>
            </a>
            <div class="collapse <?= $uploadActive ? 'show' : '' ?>" id="uploadBerkasMenu">
                <ul class="nav flex-column ms-3">
                    <li><a href="publikasi_karya.php" class="navlink <?= ($page == 'publikasi_karya.php') ? 'active' : '' ?>">Publikasi</a></li>
                    <li><a href="ppm_karya.php" class="navlink <?= ($page == 'ppm_karya.php') ? 'active' : '' ?>">PPM</a></li>
                    <li><a href="riset_karya.php" class="navlink <?= ($page == 'riset_karya.php') ? 'active' : '' ?>">Riset</a></li>
                    <li><a href="kekayaan_intelektual_karya.php" class="navlink <?= ($page == 'kekayaan_intelektual_karya.php') ? 'active' : '' ?>">Kekayaan Intelektual</a></li>
                </ul>
            </div>
        </li>

        <li>
            <a href="partners.php" class="navlink <?= ($page == 'partners.php') ? 'active' : '' ?>">
                <i class="bi bi-person-heart"></i>
                <span>Mitra</span>
            </a>
        </li>

        <li>
            <a href="gallery.php" class="navlink <?= ($page == 'gallery.php') ? 'active' : '' ?>">
                <i class="bi bi-images"></i>
                <span>Galeri</span>
            </a>
        </li>

        <li>
            <a href="news.php" class="navlink <?= ($page == 'news.php') ? 'active' : '' ?>">
                <i class="bi bi-newspaper"></i>
                <span>Berita</span>
            </a>
        </li>

        <li>
            <a href="peminjaman.php" class="navlink <?= ($page == 'peminjaman.php') ? 'active' : '' ?>">
                <i class="bi bi-journal-text"></i>
                <span>Peminjaman</span>
            </a>
        </li>

        <?php 
        $daftarActive = in_array($page, ['daftar_asisten.php','daftar_magang.php']);
        ?>
        <li>
            <a class="navlink dropdown-toggle <?= $daftarActive ? 'active' : '' ?>" data-bs-toggle="collapse" href="#kelolaDaftarMenu">
                <i class="bi bi-list-check"></i>
                <span>Kelola Daftar</span>
            </a>
            <div class="collapse <?= $daftarActive ? 'show' : '' ?>" id="kelolaDaftarMenu">
                <ul class="nav flex-column ms-3">
                    <li><a href="daftar_asisten.php" class="navlink <?= ($page == 'daftar_asisten.php') ? 'active' : '' ?>">Daftar Asisten</a></li>
                    <li><a href="daftar_magang.php" class="navlink <?= ($page == 'daftar_magang.php') ? 'active' : '' ?>">Daftar Magang</a></li>
                </ul>
            </div>
        </li>

    </ul>
</div>