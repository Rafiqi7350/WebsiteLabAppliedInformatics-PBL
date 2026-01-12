<?php
include '../../dashboard_admin/model/config_db.php';

// Ambil produk dari database
$result = pg_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!-- navbar -->
<?php include __DIR__ . '/../template/navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Lab Applied Informatics</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../template/style_product.css">
    <link rel="stylesheet" href="../template/style_dropdown.css?v=1.0">

    <style>
        .title-line {
            position: relative;
            display: inline-block;
            padding-bottom: 16px;
        }

        .title-line::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 100px;
            /* PANJANG GARIS */
            height: 4px;
            /* KETEBALAN */
            background-color: #FF6B35;
            border-radius: 2px;
            /* ujung halus */
        }

        /* Background image */
        .produk-header::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('../../dashboard_admin/assets/img/atas.jpeg');
            background-size: cover;
            background-position: center;
            opacity: 0.08;
            animation: slowZoom 22s ease-in-out infinite;
            z-index: 1;
        }
    </style>
</head>

<body>
    <!-- HERO -->
    <section class="produk-header">
        <h1>
            <span class="title-line">PRODUK kami</span>
        </h1>

    </section>
    <div class="mt-5 mb-5">

        <?php
        if (pg_num_rows($result) == 0) {
            echo "<p style='text-align:center; margin-top:40px; font-size:20px;'>Product belum tersedia.</p>";
        }
        ?>

        <?php while ($row = pg_fetch_assoc($result)) :
            $gambar = "../../dashboard_admin/assets/img/products/" . $row['gambar'];
        ?>

            <div class="product-card">
                <div class="product-text">
                    <div class="product-title"><?= htmlspecialchars($row['nama']) ?></div>
                    <div class="product-desc"><?= htmlspecialchars($row['deskripsi']) ?></div>

                    <?php if (!empty($row['link'])) : ?>
                        <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank" class="btn-detail">&gt;Detail</a>
                    <?php endif; ?>
                </div>

                <!-- LOGO -->
                <div class="product-logo">
                    <img src="<?= $gambar ?>" alt="<?= htmlspecialchars($row['nama']) ?>">
                </div>
            </div>

        <?php endwhile; ?>

    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- koneksi footer -->
    <?php include __DIR__ . '/../template/footer.php'; ?>
</body>

</html>