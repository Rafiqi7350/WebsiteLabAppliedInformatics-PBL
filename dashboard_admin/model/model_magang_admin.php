<?php
// Ambil semua data pendaftar
function getAllMagang($conn) {
    return pg_query($conn, "SELECT * FROM daftar_magang ORDER BY id DESC");
}

// Ambil pending saja (status = 'Menunggu')
function getPendingMagang($conn) {
    return pg_query($conn, "SELECT * FROM daftar_magang WHERE status = 'Menunggu' ORDER BY id DESC");
}

// Ambil yang diterima (disetujui / diterima) — dipakai di view "Diterima"
function getAcceptedMagang($conn) {
    // gunakan ILIKE supaya case-insensitive
    return pg_query($conn, "SELECT * FROM daftar_magang WHERE status ILIKE 'Disetujui' OR status ILIKE 'Diterima' ORDER BY id DESC");
}

// APPROVE: gunakan SP bila ada, jika tidak fallback ke UPDATE
function approveMagang($conn, $id) {
    return pg_query_params(
        $conn,
        "UPDATE daftar_magang 
         SET status = 'Disetujui'
         WHERE id = $1",
        [$id]
    );
}

// REJECT: gunakan SP bila ada, jika tidak fallback ke UPDATE
function rejectMagang($conn, $id) {
    return pg_query_params(
        $conn,
        "UPDATE daftar_magang 
         SET status = 'Ditolak'
         WHERE id = $1",
        [$id]
    );
}

// DELETE pendaftar
function deleteMagang($conn, $id) {
    return pg_query_params($conn, "DELETE FROM daftar_magang WHERE id = $1", [$id]);
}
