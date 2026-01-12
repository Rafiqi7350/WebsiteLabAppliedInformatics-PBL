<?php
session_start();
include __DIR__ . '/../model/config_db.php';
include __DIR__ . '/../model/model_karya.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: ../view/riset_karya.php"); exit; }

$member_dosen_id = intval($_POST['member_dosen_id'] ?? 0) ?: null;
$judul = trim($_POST['judul'] ?? '');
$tahun = intval($_POST['tahun'] ?? 0) ?: null;
$link = trim($_POST['link'] ?? null);

if ($judul === '') { header("Location: ../view/riset_karya.php?status=empty"); exit; }

$ok = insertRisetNoFile($conn, $member_dosen_id, $judul, $tahun, $link);
header("Location: ../view/riset_karya.php?status=" . ($ok ? 'success' : 'error'));
exit;
