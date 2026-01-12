<?php
include '../model/config_db.php';
include '../model/model_members.php';

$id = $_GET['id'];

deleteMember($conn, $id);

header("Location: ../view/members.php");
exit();
