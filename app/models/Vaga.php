<?php
require_once __DIR__ . "/../../config/conn.php";

class Vaga
{

    private $pdo;

    public function __construct()
    {
        $conexao = new Conexao();
        $this->pdo = $conexao->conn;
    }

    public function inserir($titulo, $conteudo)
    {
        $sql = $this->pdo->prepare("
            INSERT INTO vagas (titulo, conteudo, data_vaga) 
            VALUES (:titulo, :conteudo, now())
        ");

        $sql->bindParam(':titulo', $titulo);
        $sql->bindParam(':conteudo', $conteudo);

        return $sql->execute();
    }

    public function buscar()
    {
        $sql = $this->pdo->prepare('SELECT v.id,v.titulo,v.conteudo,v.data_vaga,
    COUNT(c.id) AS total_candidaturas
        FROM vagas v
        LEFT JOIN candidaturas c ON c.id_vaga = v.id
        GROUP BY v.id, v.titulo, v.conteudo, v.data_vaga
        ORDER BY v.data_vaga DESC;');
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca candidaturas de uma vaga.
     * 
     * @param int $idVaga ID da vaga.
     * @return array Array com as candidaturas.
     */
    public function buscarCandidaturas($idVaga)
    {
        $sql = $this->pdo->prepare('SELECT 
    c.id,
    c.data_cand,
    u.id AS id_usuario,
    u.nome,
    u.email
FROM candidaturas c
INNER JOIN usuarios u ON u.id = c.id_user
WHERE c.id_vaga = :id_vaga
ORDER BY c.data_cand DESC;');
        $sql->bindParam(':id_vaga', $idVaga);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Insere uma candidatura em uma vaga.
     * 
     * @param int $idUser ID do usu rio que est  se candidatando.
     * @param int $idVaga ID da vaga que o usu rio est  se candidatando.
     * @return bool TRUE se a candidatura for inserida com sucesso, FALSE caso contr rio.
     */
    public function inserirCandidatura($idUser, $idVaga)
    {
        $sql = $this->pdo->prepare('INSERT INTO candidaturas (id_user,id_vaga,data_cand) VALUES (:id_usuario, :id_vaga, now())');
        $sql->bindParam(':id_usuario', $idUser);
        $sql->bindParam(':id_vaga', $idVaga);
        return $sql->execute();
    }

    /**
     * Deleta uma vaga.
     * 
     * @param int $idVaga ID da vaga que ser  deletada.
     * @return bool TRUE se a vaga for deletada com sucesso, FALSE caso contr rio.
     */
    public function deletarVaga($idVaga)
    {

      
        $sql1 = $this->pdo->prepare("DELETE FROM candidaturas WHERE id_vaga = :id");
        $sql1->execute([':id' => $idVaga]);

        $sqlV =$this->pdo->prepare('UPDATE');

        $sql2 = $this->pdo->prepare("DELETE FROM vagas WHERE id = :id");
        return $sql2->execute([':id' => $idVaga]);

        
    }
}
?>