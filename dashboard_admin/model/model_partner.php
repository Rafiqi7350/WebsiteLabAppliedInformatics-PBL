<?php
// Ambil semua partner
function getAllPartners($conn) {
    return pg_query($conn, "SELECT * FROM partners ORDER BY id ASC");
}

// Ambil partner berdasarkan kategori (nama folder kategori)
function getPartnersByCategory($conn, $kategori) {
    $query = "SELECT * FROM partners WHERE kategori = $1 ORDER BY id ASC";
    return pg_query_params($conn, $query, [$kategori]);
}

// Ambil partner berdasarkan ID (untuk edit/update)
function getPartnerById($conn, $id) {
    $query = "SELECT * FROM partners WHERE id = $1";
    $result = pg_query_params($conn, $query, [$id]);
    return pg_fetch_assoc($result);
}

// Tambah partner baru
function addPartner($conn, $nama, $kategori, $logo) {
    $query = "INSERT INTO partners (nama, kategori, logo) VALUES ($1, $2, $3)";
    return pg_query_params($conn, $query, [$nama, $kategori, $logo]);
}

// Update partner
function updatePartner($conn, $id, $nama, $kategori, $logo = null) {
    if ($logo === null) {
        // Jika logo tidak diubah
        $query = "UPDATE partners SET nama=$1, kategori=$2 WHERE id=$3";
        return pg_query_params($conn, $query, [$nama, $kategori, $id]);
    } else {
        // Jika logo diganti
        $query = "UPDATE partners SET nama=$1, kategori=$2, logo=$3 WHERE id=$4";
        return pg_query_params($conn, $query, [$nama, $kategori, $logo, $id]);
    }
}

// Hapus partner
function deletePartner($conn, $id) {
    $query = "DELETE FROM partners WHERE id=$1";
    return pg_query_params($conn, $query, [$id]);
}
?>
