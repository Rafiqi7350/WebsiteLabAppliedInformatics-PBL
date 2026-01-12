<?php
// dashboard_admin/model/model_profile.php

class ModelProfile
{

    private $conn; // untuk menyimpan koneksi database

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Mengambil data profil lab
    public function getProfileLab()
    {
        $sql = "SELECT * FROM profile_lab WHERE id = 1 LIMIT 1";
        $result = pg_query($this->conn, $sql);
        return pg_fetch_assoc($result);
    }

    // update deskripsi (pengganti sejarah)
    public function updateDeskripsi($deskripsi)
    {
        $q = pg_prepare(
            $this->conn,
            "update_deskripsi",
            "UPDATE profile_lab SET deskripsi = $1 WHERE id = 1"
        );

        return pg_execute($this->conn, "update_deskripsi", [$deskripsi]);
    }

    // Update visi & misi
    public function updateVisiMisi($visi, array $misiList)
    {

        // Convert array misi ke JSON
        $misiJSON = json_encode(array_values($misiList));

        // UPDATE untuk mencegah SQL Injection 
        $sql = "
            UPDATE profile_lab
            SET visi = $1,
                misi = $2::text
            WHERE id = 1
        ";

        return pg_query_params($this->conn, $sql, [$visi, $misiJSON]);
    }
}
