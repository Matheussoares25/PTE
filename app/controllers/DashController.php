<?php
require_once __DIR__ . "/../models/DashBoard.php";

class DashController
{
    private $dash;
    public function __construct()
    {
        $this->dash = new DashBoard();
    }

    public function DELETARMATRICULADECURSO()
    {

        $matricula = $_POST["matricula"] ?? null;

        if ($matricula == null) {
            echo json_encode(["VAZIO" => true]);
            return;
        }

        $res = $this->dash->deletarMatricula($matricula);

        echo json_encode($res);
    }
}