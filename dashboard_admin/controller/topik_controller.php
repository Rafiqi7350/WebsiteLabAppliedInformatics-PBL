<?php
require_once __DIR__ . '/../model/model_topik.php';

class TopikController {

    private $topikModel;

    public function __construct($conn){
        $this->topikModel = new ModelTopik($conn);
    }

    // Method untuk menangani request POST
    public function handlePost() {

        if (isset($_POST['add_topik'])) {
            $this->topikModel->add($_POST['judul'] ?? '', $_POST['deskripsi'] ?? '');
            header("Location: info_profil.php");
            exit;
        }

        if (isset($_POST['edit_topik'])) {
            $this->topikModel->edit($_POST['id_topik'], $_POST['judul'], $_POST['deskripsi']);
            header("Location: info_profil.php");
            exit;
        }

        if (isset($_POST['delete_topik'])) {
            $this->topikModel->delete($_POST['id_topik']);
            header("Location: info_profil.php");
            exit;
        }
    }

    public function getAll() {
        return $this->topikModel->getAll();
    }
}
