<?php
session_start();
include __DIR__ . '/../model/config_db.php';
include __DIR__ . '/../model/model_upload.php';

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) { header("Location: ../view/upload_riset.php?status=error"); exit; }

$res = pg_query_params($conn, "SELECT file FROM riset WHERE id=$1", [$id]);
if ($res && pg_num_rows($res) > 0) {
  $row = pg_fetch_assoc($res);
  $filename = $row['file'] ?? '';
  $filepath = __DIR__ . '/../../uploads/riset/' . $filename;
  
  $ok = deleteRiset($conn, $id);
  
  if ($ok) { 
    if (is_file($filepath)) @unlink($filepath); 
    header("Location: ../view/upload_riset.php?status=deleted"); 
    exit; 
  }
}
header("Location: ../view/upload_riset.php?status=error");
exit;