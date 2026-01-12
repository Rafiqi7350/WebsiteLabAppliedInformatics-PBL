<?php
session_start();
include '../model/config_db.php';
include '../model/model_asisten.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ambil file upload (kalau ada)
    $berkas = $_FILES['berkas']['name'] ?? null;
    $cv = $_FILES['cv']['name'] ?? null;
    $transkrip = $_FILES['transkrip_nilai']['name'] ?? null;
    $portofolio = $_FILES['portofolio']['name'] ?? null;

 $data = [
    'nama' => $_POST['nama'] ?? '',
    'nim' => $_POST['nim'] ?? '',
    'prodi' => $_POST['prodi'] ?? '',
    'semester' => $_POST['semester'] ?? '',
    'angkatan_masuk' => $_POST['angkatan_masuk'] ?? null,
    'email' => $_POST['email'] ?? '',
    'no_hp' => $_POST['no_hp'] ?? null,
    'berkas' => $_FILES['berkas']['name'] ?? null,
    'status' => 'pending',
    'deskripsi_diri' => $_POST['deskripsi_diri'] ?? null,
    'riwayat_pengalaman' => $_POST['riwayat_pengalaman'] ?? null,
    'cv' => $_FILES['cv']['name'] ?? null,
    'transkrip_nilai' => $_FILES['transkrip_nilai']['name'] ?? null,
    'portofolio' => $_FILES['portofolio']['name'] ?? null,
    'persetujuan' => isset($_POST['persetujuan']) ? 1 : 0,
    'peran_asisten' => $_POST['peran_asisten'] ?? null,
    'admin_id' => $_SESSION['admin_id'] ?? null
];

    // Panggil model
    $insert = insertAsisten($conn, $data);

    if ($insert) {
        header("Location: ../../web_page/view/daftarAsisten.php?status=success");
        exit;
    } else {
        echo "<h3 style='color:red'>❌ ERROR INSERT DATABASE</h3>";
        echo "<pre>" . pg_last_error($conn) . "</pre>";
        exit;
    }
}
header("Location: ../../view/daftar_asisten.php?status=success");
exit();

?>
