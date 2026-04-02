<?php
require_once __DIR__ . "/../../config/conn.php";
class Curso
{
    private $pdo;

    public function __construct()
    {
        $conexao = new Conexao();
        $this->pdo = $conexao->conn;
    }

    public function buscarTreinamentosDoUsuario($idUser)
    {
        $sql = $this->pdo->prepare("SELECT a.id_usuario,a.id_curso,a.status_curso,c.nome,b.email  FROM use_treinamentos AS a 
        INNER JOIN usuarios AS b ON a.id_usuario = b.id 
         LEFT JOIN treinamentos AS c ON a.id_curso = c.id WHERE a.id_usuario = :idUser and status_curso = 1");
        $sql->bindParam(":idUser", $idUser);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);

    }

    public function buscarTreinamentosConcluidosDoUsuario($idUser)
    {
        $sql = $this->pdo->prepare("SELECT a.id_usuario,a.id_curso,a.status_curso,c.nome  FROM use_treinamentos AS a 
            INNER JOIN usuarios AS b ON a.id_usuario = b.id 
            LEFT JOIN treinamentos AS c ON a.id_curso = c.id WHERE a.id_usuario = :idUser AND a.status_curso = 1");
        $sql->bindParam(":idUser", $idUser);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarCursosComModulosEAulas()
    {
        $stmt = $this->pdo->query("
        SELECT 
            t.id AS id_treinamento,
            t.nome AS nome_treinamento,

            m.id AS id_modulo,
            m.nome_modolu AS nome_modulo,

            a.id AS id_aula,
            a.nome_aula AS nome_aula,
            a.tipo as Tipo

        FROM treinamentos t
        LEFT JOIN modulos m 
            ON m.id_curso = t.id

        LEFT JOIN aulas a 
            ON a.id_modulo = m.id
           AND a.excluido = 0

        ORDER BY t.id DESC, m.id ASC, a.id ASC
    ");

        $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultado = [];

        foreach ($linhas as $row) {

            $tid = $row['id_treinamento'];
            $mid = $row['id_modulo'];


            if (!isset($resultado[$tid])) {
                $resultado[$tid] = [
                    'id' => $tid,
                    'nome' => $row['nome_treinamento'],
                    'modulos' => []
                ];
            }


            if ($mid && !isset($resultado[$tid]['modulos'][$mid])) {
                $resultado[$tid]['modulos'][$mid] = [
                    'id_modulo' => $mid,
                    'nome_modulo' => $row['nome_modulo'],
                    'aulas' => []
                ];
            }


            if (!empty($row['id_aula'])) {
                $resultado[$tid]['modulos'][$mid]['aulas'][] = [
                    'id_aula' => $row['id_aula'],
                    'nome_aula' => $row['nome_aula'],
                    'tipo' => $row['Tipo']
                ];
            }
        }

        foreach ($resultado as &$curso) {
            $curso['modulos'] = array_values($curso['modulos']);
        }

        return array_values($resultado);
    }

    




}
?>