<?php
session_start();
include __DIR__ . '/../model/config_db.php';
include __DIR__ . '/../model/model_karya.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: ../view/kekayaan_intelektual_karya.php"); exit; }

$member_dosen_id = intval($_POST['member_dosen_id'] ?? 0) ?: null;
$judul = trim($_POST['judul'] ?? '');
$tahun = intval($_POST['tahun'] ?? 0) ?: null;
$nomor = trim($_POST['nomor_permohonan'] ?? null);

if ($judul === '') { header("Location: ../view/kekayaan_intelektual_karya.php?status=empty"); exit; }

$ok = insertHkiNoFile($conn, $member_dosen_id, $judul, $tahun, $nomor);
header("Location: ../view/kekayaan_intelektual_karya.php?status=" . ($ok ? 'success' : 'error'));
exit;
