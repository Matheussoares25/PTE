<?php
require_once __DIR__ . "/../../config/conn.php";

class DashBoard
{
    private $pdo;
    public function __construct()
    {
        $conexao = new conexao();
        $this->pdo = $conexao->conn;
    }

    public function deletarMatricula($matricula){
        $sql = $this->pdo->prepare("DELETE FROM use_treinamentos where matricula = :matricula");
        $sql->bindParam(":matricula", $matricula);
        $sql->execute();

        return (["success" => true]);
    }
}