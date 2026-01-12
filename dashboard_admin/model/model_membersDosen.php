<?php
// model_memberDosen.php
// Pastikan $conn dari config_db.php sudah tersedia

// Ambil semua data members dosen
function getAllMembersDosen($conn) {
    $query = "SELECT * FROM member_dosen ORDER BY id";
    $result = pg_query($conn, $query);

    if (!$result) {
        // Kalau query gagal, hentikan dan tampilkan error
        die("Query gagal: " . pg_last_error($conn));
    }

    return $result;
}

// Ambil satu dosen berdasarkan ID
function getMemberDosenById($conn, $id) {
    $query = "SELECT * FROM member_dosen WHERE id = $1";
    $result = pg_query_params($conn, $query, array($id));

    if (!$result) {
        die("Query gagal: " . pg_last_error($conn));
    }

    return pg_fetch_assoc($result); // kembalikan satu record
}

// Tambah member dosen
function addMemberDosen($conn, $nama, $role, $expertise, $deskripsi, $foto) {
    $query = "INSERT INTO member_dosen (nama, role, expertise, deskripsi, foto) VALUES ($1,$2,$3,$4,$5)";
    $result = pg_query_params($conn, $query, array($nama, $role, $expertise, $deskripsi, $foto));

    if (!$result) {
        die("Gagal menambah member: " . pg_last_error($conn));
    }

    return true;
}

// Update member dosen
function updateMemberDosen($conn, $id, $nama, $role, $expertise, $deskripsi, $foto = null) {
    if ($foto) {
        $query = "UPDATE member_dosen SET nama=$1, role=$2, expertise=$3, deskripsi=$4, foto=$5 WHERE id=$6";
        $params = array($nama, $role, $expertise, $deskripsi, $foto, $id);
    } else {
        $query = "UPDATE member_dosen SET nama=$1, role=$2, expertise=$3, deskripsi=$4 WHERE id=$5";
        $params = array($nama, $role, $expertise, $deskripsi, $id);
    }

    $result = pg_query_params($conn, $query, $params);

    if (!$result) {
        die("Gagal update member: " . pg_last_error($conn));
    }

    return true;
}

// Hapus member dosen
function deleteMemberDosen($conn, $id) {
    $query = "DELETE FROM member_dosen WHERE id=$1";
    $result = pg_query_params($conn, $query, array($id));

    if (!$result) {
        die("Gagal hapus member: " . pg_last_error($conn));
    }

    return true;
}

// Update sosial media member dosen
function updateSocialMedia($conn, $id, $scholar, $sinta, $scopus, $orcid) {
    $query = "
        UPDATE member_dosen 
        SET scholar_link = $1,
            sinta_link   = $2,
            scopus_link  = $3,
            orcid_link   = $4
        WHERE id = $5
    ";

    $params = array($scholar, $sinta, $scopus, $orcid, $id);

    $result = pg_query_params($conn, $query, $params);

    if (!$result) {
        die("Gagal update social media: " . pg_last_error($conn));
    }

    return true;
}
