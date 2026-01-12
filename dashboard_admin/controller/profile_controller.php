<?php
require_once __DIR__ . '/../model/model_profile.php';

class ProfileController {

    private $model;
    private $conn; 

    public function __construct($conn){
        $this->conn = $conn; 
        $this->model = new ModelProfile($conn);
    }

    // Method untuk menangani request POST 
    public function handlePost(){

        // ----------- SAVE VISI & MISI -----------
        if (isset($_POST['save_visi_misi'])) {

            $visi = $_POST['visi'] ?? "";
            $misi = $_POST['misi'] ?? [];

            if (!is_array($misi)) {
                $misi = [];
            }

            $misi_json = json_encode($misi);

            $query = "UPDATE profile_lab SET visi = $1, misi = $2 WHERE id = 1";
            pg_query_params($this->conn, $query, [$visi, $misi_json]);

            header("Location: info_profil.php?saved=1");
            exit;
        }


        // ----------- SAVE DESKRIPSI (dulunya sejarah) -----------
        if (isset($_POST['save_deskripsi'])) {

            $deskripsi = $_POST['deskripsi'] ?? "";

            // UPDATE DB kolom sudah bernama deskripsi
            $query = "UPDATE profile_lab SET deskripsi = $1 WHERE id = 1";
            pg_query_params($this->conn, $query, [$deskripsi]);

            header("Location: info_profil.php?deskripsi_saved=1");
            exit;
        }
    }

    public function getProfile(){
        return $this->model->getProfileLab();
    }
}
