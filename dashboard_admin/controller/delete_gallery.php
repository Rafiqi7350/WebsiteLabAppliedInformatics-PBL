<?php
include '../model/config_db.php';
include '../model/model_gallery.php';

$id = $_GET['id'];

deleteGallery($conn, $id);

header("Location: ../view/gallery.php");
exit();
