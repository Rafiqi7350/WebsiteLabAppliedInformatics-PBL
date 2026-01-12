<?php
// dashboard_admin/model/model_topik.php

class ModelTopik {

    private $conn;
    public function __construct($conn){
        $this->conn = $conn;
    }

    //tata letak berdasarkan urutan topik riset
    public function getAll() {
        return pg_query($this->conn, "SELECT * FROM topik_riset ORDER BY urutan ASC");
    }

    public function add($judul, $deskripsi) {
        $j = pg_escape_string($this->conn, $judul);
        $d = pg_escape_string($this->conn, $deskripsi);

        return pg_query($this->conn, "
            INSERT INTO topik_riset (judul, deskripsi, urutan)
            VALUES ('{$j}','{$d}', (SELECT COALESCE(MAX(urutan),0)+1 FROM topik_riset))
        ");
    }

    //memastikan data input aman & valid sebelum dipakai di database
    public function edit($id, $judul, $deskripsi) {
        $j = pg_escape_string($this->conn, $judul);
        $d = pg_escape_string($this->conn, $deskripsi);
        $id = intval($id);

        return pg_query($this->conn, "
            UPDATE topik_riset 
            SET judul='{$j}', deskripsi='{$d}' 
            WHERE id = {$id}
        ");
    }

    public function delete($id) {
        $id = intval($id);
        return pg_query($this->conn, "DELETE FROM topik_riset WHERE id = {$id}");
    }
}
