<?php
include '../model/config_db.php';
include '../model/model_products.php';

$id = $_GET['id'];

deleteProduct($conn, $id);

header("Location: ../view/products.php");
exit();
