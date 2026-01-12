<?php
include '../model/config_db.php';
include '../model/model_partner.php';

$id       = $_POST['id'];
$nama     = $_POST['nama'];
$kategori = $_POST['kategori'];

// Mapping kategori → folder
$folderMap = [
    "Industry Partner"           => "industry_partner",
    "Educational Institutions"   => "educational_institutions",
    "Governement Institutions"   => "government_institutions",
    "International Institutions" => "international_institutions"
];

if (!isset($folderMap[$kategori])) die("Kategori tidak valid!");

// Ambil data partner lama
$partner = getPartnerById($conn, $id);
if (!$partner) die("Partner tidak ditemukan!");

$logo = $partner['logo']; // default pakai logo lama
$oldFolder = $folderMap[$partner['kategori']];
$newFolder = $folderMap[$kategori];

// Jika ada upload logo baru
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['logo']['tmp_name'];
    $logo = basename($_FILES['logo']['name']);

    $uploadDir = "../assets/img/partners/$newFolder/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    move_uploaded_file($tmp, $uploadDir . $logo);
} else if ($kategori !== $partner['kategori']) {
    // Jika kategori berubah tapi logo tidak diganti → pindahkan file logo lama ke folder baru
    $oldPath = "../assets/img/partners/$oldFolder/$logo";
    $newPath = "../assets/img/partners/$newFolder/$logo";
    if (!is_dir("../assets/img/partners/$newFolder/")) mkdir("../assets/img/partners/$newFolder/", 0777, true);
    if (file_exists($oldPath)) rename($oldPath, $newPath);
}

// Update database
updatePartner($conn, $id, $nama, $kategori, $logo);

header("Location: ../view/partners.php");
exit();
?>
