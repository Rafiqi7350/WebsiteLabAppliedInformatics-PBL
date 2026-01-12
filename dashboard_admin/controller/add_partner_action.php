<?php
include '../model/config_db.php';
include '../model/model_partner.php';

// Ambil data
$nama = $_POST['nama'];
$kategoriInput = $_POST['kategori'];

// Mapping kategori → folder
$folderMap = [
    "Industry Partner"           => "industry_partner",
    "Educational Institutions"   => "educational_institutions",
    "Governement Institutions"   => "government_institutions",
    "International Institutions" => "international_institutions"
];

// Validasi kategori
if (!isset($folderMap[$kategoriInput])) {
    die("Kategori tidak valid!");
}

$folder = $folderMap[$kategoriInput];

// File logo
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $tmp  = $_FILES['logo']['tmp_name'];
    $logo = basename($_FILES['logo']['name']);

    $uploadDir = "../assets/img/partners/$folder/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    // Pindahkan file
    move_uploaded_file($tmp, $uploadDir . $logo);

    // Simpan ke database
    addPartner($conn, $nama, $kategoriInput, $logo);

    header("Location: ../view/partners.php");
    exit();
} else {
    die("Error upload file logo!");
}
?>
