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

    /**
     * Deleta uma matrícula do cadastro de cursos
     *
     * @param int $matricula Matrícula a ser deletada
     *
     * @return array array com chave "success" e valor true
     */
    public function deletarMatricula($matricula){
        $sql = $this->pdo->prepare("DELETE FROM use_treinamentos where matricula = :matricula");
        $sql->bindParam(":matricula", $matricula);
        $sql->execute();

        return (["success" => true]);
    }
    public function deletaReport($id){
       
        $sql = $this->pdo->prepare("DELETE FROM reports where id = :id");
        $sql->bindParam(":id", $id);
        $sql->execute();

        return ["success"=> true];
    }
}