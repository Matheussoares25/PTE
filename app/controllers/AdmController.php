<?php
require_once __DIR__ . "/../models/Adm.php";

class AdmController
{

    private $admUser;

    public function __construct()
    {
        $this->admUser = new adm();
    }

    public function get_adm()
    {
        session_start();

        $email = $_SESSION["email"];
        $senha = $_POST['senha'] ?? null;

        if (!$senha) {
            echo json_encode(["success" => false, "mensagem" => "Senha obrigatoria"]);
            return;
        }

        $res = $this->admUser->chekin($senha, $email);

        echo json_encode($res);
    }
}
?>