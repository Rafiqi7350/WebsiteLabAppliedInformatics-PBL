<?php

// INSERT DATA ASISTEN
function insertAsisten($conn, $data)
{
    $query = "
        CALL public.sp_add_rekrut_asisten(
            $1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16
        )
    ";

    $params = [
        $data['nama'],
        $data['nim'],
        $data['prodi'],
        $data['semester'],
        $data['angkatan_masuk'],
        $data['email'],
        $data['no_hp'],
        $data['berkas'],          // bisa diisi 'Menunggu'
        $data['status'],          // bisa diisi 'pending'
        $data['deskripsi_diri'],
        $data['riwayat_pengalaman'],
        $data['cv'],
        $data['transkrip_nilai'],
        $data['portofolio'],
        $data['persetujuan'],     // false atau true
        $data['peran_asisten']
    ];

    $result = pg_query_params($conn, $query, $params);

    if (!$result) {
        // Bisa ditambahkan logging error
        error_log(pg_last_error($conn));
        return false;
    }

    return true;
}

// AMBIL SEMUA DATA ASISTEN
function getAllAsisten($conn)
{
    $query = "SELECT * FROM rekrut_asisten ORDER BY id DESC";
    return pg_query($conn, $query);
}

// ACCEPT (MENERIMA) ASISTEN
function acceptAsisten($conn, $id)
{
    $query = "UPDATE rekrut_asisten SET status = $1 WHERE id = $2";
    $params = ['diterima', $id];

    return pg_query_params($conn, $query, $params);
}

// REJECT (MENOLAK) ASISTEN
function rejectAsisten($conn, $id)
{
    $query = "UPDATE rekrut_asisten SET status = $1 WHERE id = $2";
    $params = ['ditolak', $id];

    return pg_query_params($conn, $query, $params);
}

// DELETE ASISTEN
function deleteAsisten($conn, $id)
{
    $query = "DELETE FROM rekrut_asisten WHERE id = $1";
    $params = [$id];

    return pg_query_params($conn, $query, $params);
}
