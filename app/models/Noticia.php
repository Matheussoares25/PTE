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

    /**
     * Insere uma noticia no banco de dados.
     * 
     * @param string $titulo O titulo da noticia.
     * @param string $conteudo O conteudo da noticia.
     * @param string $data A data da noticia.
     * @param int $vaga O tipo de noticia (1 para vagas, 0 para noticia normal).
     * @return bool True se a noticia for inserida com sucesso, false caso contr rio.
     */
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

    /**
     * Busca todas as notcias no banco de dados.
     *
     * @return array Um array associativo com todas as notcias do banco de dados.
     */
    public function buscarNoticias()
    {
        $sql = $this->pdo->prepare("SELECT id, titulo, conteudo,data_noticia,vaga FROM noticias");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Deleta uma noticia no banco de dados.
     * 
     * @param int $id O id da noticia a ser deletada.
     * @return bool True se a noticia for deletada com sucesso, false caso contr rio.
     */
    public function deleteNoticias($id)
    {
        $sql = $this->pdo->prepare("DELETE FROM noticias WHERE id = :id");
        $sql->bindParam(":id", $id);

        return $sql->execute();
    }
}