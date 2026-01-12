<?php

function insertPeminjaman($conn, $data)
{
    $query = "
        CALL public.sp_add_peminjaman(
            $1::varchar,
            $2::varchar,
            $3::varchar,
            $4::varchar,
            $5::varchar,
            $6::date,
            $7::date,
            $8::time,
            $9::time
        )
    ";

    $params = [
        $data['nama'],
        $data['nim'],
        $data['email'],
        $data['no_hp'],
        $data['keperluan'],
        $data['tanggal_mulai'],
        $data['tanggal_selesai'],
        $data['waktu_mulai'],
        $data['waktu_selesai']
    ];

    $result = pg_query_params($conn, $query, $params);

    if (!$result) {
        error_log(pg_last_error($conn));
        return false;
    }

    return true;
}
?>
