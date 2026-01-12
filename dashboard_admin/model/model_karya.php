<?php
// Model untuk publikasi / riset / ppm / kekayaan intelektual

// PUBLIKASI
function getAllPublikasi($conn) {
    return pg_query($conn, "SELECT * FROM publikasi ORDER BY id DESC");
}

// sp
function insertPublikasiNoFile($conn, $member_dosen_id, $judul, $tahun, $link = null) {
    return pg_query_params(
        $conn,
        "CALL public.sp_add_publikasi($1, $2, $3, $4)",
        [$member_dosen_id, $judul, $tahun, $link]
    );
}

function deletePublikasi($conn, $id) {
    return pg_query_params($conn, "DELETE FROM publikasi WHERE id=$1", [$id]);
}

// RISET
function getAllRiset($conn) {
    return pg_query($conn, "SELECT * FROM riset ORDER BY id DESC");
}

// sp
function insertRisetNoFile($conn, $member_dosen_id, $judul, $tahun, $link = null) {
    return pg_query_params(
        $conn,
        "CALL public.sp_add_riset($1, $2, $3, $4)",
        [$member_dosen_id, $judul, $tahun, $link]
    );
}

function deleteRiset($conn, $id) {
    return pg_query_params($conn, "DELETE FROM riset WHERE id=$1", [$id]);
}

function getRisetByDosen($conn, $dosen_id)
{
    $query = "
        SELECT r.*
        FROM riset r
        WHERE r.member_dosen_id = $1
        ORDER BY r.tahun DESC, r.id DESC
    ";

    return pg_query_params($conn, $query, [$dosen_id]);
}


// PPM
function getAllPpm($conn) {
    return pg_query($conn, "SELECT * FROM ppm ORDER BY id DESC");
}

// sp
function insertPpmNoFile($conn, $member_dosen_id, $judul, $tahun, $link = null) {
    return pg_query_params(
        $conn,
        "CALL public.sp_add_ppm($1, $2, $3, $4)",
        [$member_dosen_id, $judul, $tahun, $link]
    );
}

function deletePpm($conn, $id) {
    return pg_query_params($conn, "DELETE FROM ppm WHERE id=$1", [$id]);
}
function getPpmByDosen($conn, $dosen_id)
{
    $sql = "
        SELECT p.*
        FROM ppm p
        WHERE p.member_dosen_id = $1
        ORDER BY p.tahun DESC, p.id DESC
    ";

    $result = pg_query_params($conn, $sql, [$dosen_id]);
    return $result;
}

// KEKAYAAN INTELEKTUAL (HKI)
function getAllHki($conn) {
    return pg_query($conn, "SELECT * FROM kekayaan_intelektual ORDER BY id DESC");
}
function insertHkiNoFile($conn, $member_dosen_id, $judul, $tahun, $nomor_permohonan = null) {
    return pg_query_params($conn,
        "INSERT INTO kekayaan_intelektual (member_dosen_id, judul, tahun, nomor_permohonan) VALUES ($1,$2,$3,$4)",
        [$member_dosen_id, $judul, $tahun, $nomor_permohonan]
    );
}

// sp
function insertHki($conn, $member_dosen_id, $judul, $tahun, $nomor = null) {
    return pg_query_params(
        $conn,
        "CALL public.sp_add_ki($1, $2, $3, $4)",
        [$member_dosen_id, $judul, $tahun, $nomor]
    );
}
function deleteHki($conn, $id) {
    return pg_query_params($conn, "DELETE FROM kekayaan_intelektual WHERE id=$1", [$id]);
}

function getPublikasiByDosen($conn, $dosen_id) {
    $query = "SELECT * FROM publikasi WHERE member_dosen_id = $1 ORDER BY tahun DESC";
    return pg_query_params($conn, $query, array($dosen_id));
}

function getHkiByDosen($conn, $dosen_id)
{
    $query = "
        SELECT h.*
        FROM hki h
        WHERE h.member_dosen_id = $1
        ORDER BY h.tahun DESC, h.id DESC
    ";

    return pg_query_params($conn, $query, [$dosen_id]);
}



