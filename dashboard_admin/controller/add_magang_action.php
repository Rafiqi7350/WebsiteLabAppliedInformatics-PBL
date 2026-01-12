<?php
session_start();
include __DIR__ . '/../model/config_db.php';
include __DIR__ . '/../model/model_magang.php';

// Jika bukan POST → redirect
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../web_page/view/daftarMagang.php");
    exit;
}

// Ambil input
$nama = trim($_POST['nama'] ?? '');
$nim = trim($_POST['nim'] ?? '');
$prodi = trim($_POST['prodi'] ?? '');
$email = trim($_POST['email'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');

// Base folder
$baseUpload = __DIR__ . '/../assets/daftar_magang/';

// Pastikan folder utama ada
if (!is_dir($baseUpload)) mkdir($baseUpload, 0755, true);

// Buat folder khusus
$cvDir     = $baseUpload . 'cv/';
$ktmDir    = $baseUpload . 'ktm/';
$suratDir  = $baseUpload . 'surat/';

foreach ([$cvDir, $ktmDir, $suratDir] as $folder) {
    if (!is_dir($folder)) mkdir($folder, 0755, true);
}

// Fungsi upload file
function uploadFile($field, $dir)
{
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $tmp = $_FILES[$field]['tmp_name'];
    $orig = basename($_FILES[$field]['name']);
    $safe = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $orig);

    move_uploaded_file($tmp, $dir . $safe);
    return $safe;
}

// Upload ke folder masing-masing
$cv    = uploadFile('cv', $cvDir);
$ktm   = uploadFile('ktm', $ktmDir);
$surat = uploadFile('surat_pengantar', $suratDir);

// Data ke model
$data = [
    'nama' => $nama,
    'nim' => $nim,
    'prodi' => $prodi,
    'email' => $email,
    'no_hp' => $no_hp,
    'cv' => $cv,
    'ktm' => $ktm,
    'surat_pengantar' => $surat,
    'admin_id' => $_SESSION['admin_id'] ?? null
];

$success = insertMagang($conn, $data);

if ($success) {
    header("Location: ../../web_page/view/daftarMagang.php?status=success");
    exit;
} else {
    echo "<h3 style='color:red'>❌ ERROR INSERT DATABASE</h3>";
    echo "<pre>" . pg_last_error($conn) . "</pre>";
    exit;
}
