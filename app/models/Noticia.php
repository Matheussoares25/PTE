<?php
require_once __DIR__ . "/../../config/conn.php";

class Noticia
{

    private $pdo;

    public function __construct()
    {
        $conexao = new Conexao();
        $this->pdo = $conexao->conn;
    }

    public function inserir($titulo, $conteudo, $data, $vaga)
    {


        $sql = $this->pdo->prepare("
            INSERT INTO noticias (titulo, conteudo, data_noticia, vaga) 
            VALUES (:titulo, :conteudo, :data, :vaga)
        ");

        $sql->bindParam(':titulo', $titulo);
        $sql->bindParam(':conteudo', $conteudo);
        $sql->bindParam(':data', $data);
        $sql->bindParam(':vaga', $vaga);

        return $sql->execute();
    }

    public function buscarNoticias()
    {
        $sql = $this->pdo->prepare("SELECT id, titulo, conteudo,data_noticia,vaga FROM noticias");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteNoticias($id)
    {
        $sql = $this->pdo->prepare("DELETE FROM noticias WHERE id = :id");
        $sql->bindParam(":id", $id);

        return $sql->execute(); // 👈 retorna true/false
    }
}