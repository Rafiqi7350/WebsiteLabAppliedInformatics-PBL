<?php
include '../model/config_db.php';
include '../model/model_peminjaman.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'nama' => $_POST['nama'],
        'nim' => $_POST['nim'],
        'email' => $_POST['email'],
        'no_hp' => $_POST['nohp'],
        'keperluan' => $_POST['keperluan'],
        'tanggal_mulai' => $_POST['tgl_mulai'],
        'tanggal_selesai' => $_POST['tgl_selesai'],
        'waktu_mulai' => $_POST['jam_mulai'],
        'waktu_selesai' => $_POST['jam_selesai']
    ];

    $insert = insertPeminjaman($conn, $data);

    if ($insert) {
        header("Location: ../../web_page/view/peminjaman.php ?status=success");
        exit;
    } else {
        echo "<h3 style='color:red'>❌ ERROR INSERT DATABASE</h3>";
        echo "<pre>";
        print_r(pg_last_error($conn));  // WAJIB
        echo "</pre>";
        exit;
    }
}
?>
