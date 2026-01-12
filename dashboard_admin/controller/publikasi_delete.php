<?php
session_start();
include __DIR__ . '/../model/config_db.php';
include __DIR__ . '/../model/model_karya.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: ../view/publikasi_karya.php?status=error"); exit; }

$ok = deletePublikasi($conn, $id);
header("Location: ../view/publikasi_karya.php?status=" . ($ok ? 'deleted' : 'error'));
exit;
