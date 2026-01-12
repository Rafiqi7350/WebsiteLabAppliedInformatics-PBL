<?php
include '../model/config_db.php';
include '../model/model_membersMahasiswa.php';

$nim            = $_POST['nim'];
$nama           = $_POST['nama'];
$role           = $_POST['role'];
$jurusan        = $_POST['jurusan'];
$program_studi  = $_POST['program_studi'];

/* ================= FOTO ================= */
$foto = '';
if (!empty($_FILES['foto']['name'])) {
    $foto = time() . '_' . $_FILES['foto']['name'];
    move_uploaded_file(
        $_FILES['foto']['tmp_name'],
        "../assets/img/members/mahasiswa/" . $foto
    );
}

/* ================= INSERT ================= */
addMembersMahasiswa(
    $conn,
    $nim,
    $nama,
    $role,
    $jurusan,
    $program_studi,
    $foto
);

header("Location: ../view/members_mahasiswa.php");
exit;
