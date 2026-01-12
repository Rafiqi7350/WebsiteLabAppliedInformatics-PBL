<?php

/* ===================== GET ALL ===================== */
function getAllMembersMahasiswa($conn)
{
    return pg_query($conn, 'SELECT * FROM member_mahasiswa ORDER BY id ASC');
}

/* ===================== ADD ===================== */
function addMembersMahasiswa($conn, $nim, $nama, $role, $jurusan, $program_studi, $foto)
{
    $query = '
        INSERT INTO member_mahasiswa
        (nim, nama, role, jurusan, program_studi, foto)
        VALUES ($1, $2, $3, $4, $5, $6)
    ';

    return pg_query_params($conn, $query, [
        $nim,
        $nama,
        $role,
        $jurusan,
        $program_studi,
        $foto
    ]);
}

/* ===================== UPDATE ===================== */
function updateMembersMahasiswa($conn, $id, $nim, $nama, $role, $jurusan, $program_studi, $foto)
{
    if ($foto === '') {
        $query = '
            UPDATE member_mahasiswa
            SET nim=$1, nama=$2, role=$3, jurusan=$4, program_studi=$5
            WHERE id=$6
        ';

        return pg_query_params($conn, $query, [
            $nim,
            $nama,
            $role,
            $jurusan,
            $program_studi,
            $id
        ]);
    }

    $query = '
        UPDATE member_mahasiswa
        SET nim=$1, nama=$2, role=$3, jurusan=$4, program_studi=$5, foto=$6
        WHERE id=$7
    ';

    return pg_query_params($conn, $query, [
        $nim,
        $nama,
        $role,
        $jurusan,
        $program_studi,
        $foto,
        $id
    ]);
}

/* ===================== DELETE ===================== */
function deleteMembersMahasiswa($conn, $id)
{
    return pg_query_params(
        $conn,
        'DELETE FROM member_mahasiswa WHERE id=$1',
        [$id]
    );
}
