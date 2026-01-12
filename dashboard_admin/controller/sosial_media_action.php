<?php
include '../model/config_db.php';
include '../model/model_membersDosen.php';

// =====================
// ADD / UPDATE SOSIAL MEDIA
// =====================
if (isset($_POST['save_social'])) {
    $id = intval($_POST['dosen_id']);
    
    // Ambil data dan convert empty string ke null
    $scholar = !empty($_POST['scholar_link']) ? trim($_POST['scholar_link']) : null;
    $sinta   = !empty($_POST['sinta_link']) ? trim($_POST['sinta_link']) : null;
    $scopus  = !empty($_POST['scopus_link']) ? trim($_POST['scopus_link']) : null;
    $orcid   = !empty($_POST['orcid_link']) ? trim($_POST['orcid_link']) : null;

    $result = updateSocialMedia($conn, $id, $scholar, $sinta, $scopus, $orcid);

    if ($result) {
        header("Location: ../view/members_dosen.php?msg=social_updated");
    } else {
        header("Location: ../view/members_dosen.php?msg=social_failed");
    }
    exit;
}

// =====================
// DELETE SOSIAL MEDIA
// =====================
if (isset($_POST['delete_social'])) {
    $id = intval($_POST['dosen_id']);

    $result = updateSocialMedia($conn, $id, null, null, null, null);

    if ($result) {
        header("Location: ../view/members_dosen.php?msg=social_deleted");
    } else {
        header("Location: ../view/members_dosen.php?msg=delete_failed");
    }
    exit;
}

// Jika tidak ada action yang valid
header("Location: ../view/members_dosen.php?msg=invalid_action");
exit;
?>