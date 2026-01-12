<?php
include '../model/config_db.php';
include '../model/model_membersMahasiswa.php';

$id             = $_POST['id'];
$nim            = $_POST['nim'];
$nama           = $_POST['nama'];
$role           = $_POST['role'];
$jurusan        = $_POST['jurusan'];
$program_studi  = $_POST['program_studi'];
$foto_lama      = $_POST['foto_lama'];

/* ================= FOTO ================= */
$foto = $foto_lama;

if (!empty($_FILES['foto']['name'])) {
    $foto = time() . '_' . $_FILES['foto']['name'];
    move_uploaded_file(
        $_FILES['foto']['tmp_name'],
        "../assets/img/members/mahasiswa/" . $foto
    );
}

/* ================= UPDATE ================= */
updateMembersMahasiswa(
    $conn,
    $id,
    $nim,
    $nama,
    $role,
    $jurusan,
    $program_studi,
    $foto
);

header("Location: ../view/members_mahasiswa.php");
exit;
