<?php
// Ambil semua peminjaman pending
function getPendingPeminjaman($conn) {
    $query = "SELECT * FROM peminjaman_ruang WHERE status = 'pending'";
    return pg_query($conn, $query);
}

// Ambil semua peminjaman diterima
function getAcceptedPeminjaman($conn) {
    $query = "SELECT * FROM peminjaman_ruang WHERE status = 'diterima'";
    return pg_query($conn, $query);
}

// Ambil semua peminjaman diterima
function getRejectPeminjaman($conn) {
    $query = "SELECT * FROM peminjaman_ruang WHERE status = 'ditolak'";
    return pg_query($conn, $query);
}

// Terima peminjaman
function acceptPeminjaman($conn, $id) {
    $query = "UPDATE peminjaman_ruang SET status = 'diterima' WHERE id = $1";
    return pg_query_params($conn, $query, [$id]);
}

// Tolak peminjaman (hapus data)
function rejectPeminjaman($conn, $id) {
     $query = "UPDATE peminjaman_ruang SET status = 'ditolak' WHERE id = $1";
    return pg_query_params($conn, $query, [$id]);
}
?>
