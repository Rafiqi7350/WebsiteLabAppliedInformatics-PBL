<?php
include '../model/config_db.php';
include '../model/model_partner.php';

$id = $_GET['id'];

deletePartner($conn, $id);

header("Location: ../view/partners.php");
exit();
