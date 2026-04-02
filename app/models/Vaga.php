<?php
require_once __DIR__ . "/../../config/conn.php";

class Vaga {

    private $pdo;

    public function __construct() {
        $conexao = new Conexao();
        $this->pdo = $conexao->conn;
    }

    public function inserir($titulo, $conteudo) {
        $sql = $this->pdo->prepare("
            INSERT INTO vagas (titulo, conteudo, data_vaga) 
            VALUES (:titulo, :conteudo, now())
        ");

        $sql->bindParam(':titulo', $titulo);
        $sql->bindParam(':conteudo', $conteudo);

        return $sql->execute();
    }

    public function buscar(){
        $sql = $this->pdo->prepare('SELECT * FROM vagas');
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
        
    }

    public function buscarCandidaturas($idUser, $idVaga){
         $sql = $this->pdo->prepare('SELECT * FROM candidaturas WHERE id_user = :id_usuario AND id_vaga = :id_vaga');
         $sql->bindParam(':id_usuario', $idUser);
         $sql->bindParam('id_vaga', $idVaga);
         $sql->execute();
         return $sql->fetchAll(PDO::FETCH_ASSOC);
    }   

    public function inserirCandidatura($idUser, $idVaga){
        $sql = $this->pdo->prepare('INSERT INTO candidaturas (id_user,id_vaga,data_cand) VALUES (:id_usuario, :id_vaga, now())');
        $sql->bindParam(':id_usuario', $idUser);
        $sql->bindParam(':id_vaga', $idVaga);
        return $sql->execute();
    }
}
?>