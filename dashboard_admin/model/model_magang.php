<?php

function insertMagang($conn, $data) {
    $query = "
        CALL sp_add_magang(
            $1,$2,$3,$4,$5,$6,$7,$8
        )
    ";

    $params = [
        $data['nama'],
        $data['nim'],
        $data['prodi'],
        $data['email'],
        $data['no_hp'],
        $data['cv'],              // nama file CV
        $data['ktm'],             // nama file KTM
        $data['surat_pengantar']  // nama file surat pengantar
    ];

    $result = pg_query_params($conn, $query, $params);

    if (!$result) {
        error_log(pg_last_error($conn));
        return false;
    }

    return true;
}
?>
