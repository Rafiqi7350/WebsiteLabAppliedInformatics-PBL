<?php
include '../model/config_db.php';
include '../model/model_news.php';

$id = $_GET['id'];

deleteNews($conn, $id);

header("Location: ../view/news.php");
exit();
