<?php
require_once __DIR__ . "/../../config/conn.php";

class Certificados
{
    private $pdo;
    public function __construct()
    {
        $conexao = new Conexao();
        $this->pdo = $conexao->conn;
    }

 

    public function obterCertificados($id_user, $curso)
    {
        $sql = $this->pdo->prepare("SELECT COUNT(*) as total FROM certificado where id_user = :id and curso = :curso");
        $sql->bindParam(":id", $id_user);
        $sql->bindParam(":curso", $curso);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);

    }

    public function gerarCertificado($id_user, $Curso){

        $tokenGenerated = bin2hex(random_bytes(64));
     

        $sql = $this->pdo->prepare("INSERT into certificado(id_user, emitido, Curso, token) values (:id_user, now(), :Curso, :token)");

        $sql->bindParam(":id_user", $id_user);
        $sql->bindParam(":Curso", $Curso);
        $sql->bindParam(":token", $tokenGenerated);
        return $sql->execute();

        
    }

    public function deletarCertificado(){
        
    }

    public function editarCertificado(){
        
    }

    public function atualizarCurso($id_user, $idCurso){
        
      $sql = $this->pdo->prepare("UPDATE use_treinamentos set status_curso = 2, data_fim = now() where id_usuario = :id and id_curso = :curso");
      $sql->bindParam(":id", $id_user);
      $sql->bindParam(":curso", $idCurso);
      return $sql->execute();
    }

    public function buscartodosCertificados(){

    $sql = $this->pdo->prepare("SELECT a.id AS id_certificado, a.token,a.Curso as id_curso, a.emitido, a.id_user, b.nome AS nome_usuario, c.nome AS nome_curso
    FROM certificado AS a
    LEFT JOIN usuarios AS b  ON a.id_user = b.id
    LEFT JOIN treinamentos AS c ON a.Curso = c.id");
    $sql->execute();
    return $sql->fetchAll(PDO::FETCH_ASSOC); 
    }

    public function buscarCertificadosPorid($id_user){
        $sql = $this->pdo->prepare("SELECT a.id AS id_certificado, a.token,a.Curso as id_curso, a.emitido, a.id_user, b.nome AS nome_usuario, c.nome AS nome_curso
    FROM certificado AS a
    LEFT JOIN usuarios AS b  ON a.id_user = b.id
    LEFT JOIN treinamentos AS c ON a.Curso = c.id
    WHERE  a.id_user = :idUser");
        $sql->bindParam(":idUser", $id_user, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>