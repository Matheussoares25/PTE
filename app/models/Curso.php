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

    /**
     * Busca os treinamentos do usuario
     * 
     * @param int $idUser ID do usuário
     * @return array Contendo os treinamentos do usuário
     */
    public function buscarTreinamentosDoUsuario($idUser)
    {
        $sql = $this->pdo->prepare("SELECT a.id_usuario,a.id_curso,a.status_curso,c.nome,b.email  FROM use_treinamentos AS a 
        INNER JOIN usuarios AS b ON a.id_usuario = b.id 
         LEFT JOIN treinamentos AS c ON a.id_curso = c.id WHERE a.id_usuario = :idUser and status_curso = 1");
        $sql->bindParam(":idUser", $idUser);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Busca os treinamentos concluídos do usuário
     * 
     * @param int $idUser ID do usuário
     * @return array Contendo os treinamentos concluídos do usuário
     */
    public function buscarTreinamentosConcluidosDoUsuario($idUser)
    {
        $sql = $this->pdo->prepare("SELECT a.id_usuario,a.id_curso,a.status_curso,c.nome as nome_curso,a.data_fim,b.nome as nome_usuario  FROM use_treinamentos AS a 
            INNER JOIN usuarios AS b ON a.id_usuario = b.id 
            LEFT JOIN treinamentos AS c ON a.id_curso = c.id WHERE a.id_usuario = :idUser AND a.status_curso = 2");
        $sql->bindParam(":idUser", $idUser);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca os treinamentos com módulos e aulas
     * 
     * @return array Contendo os treinamentos com módulos e aulas
     */
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




    public function progressoAssistido($idAula)
    {

        try {
            $sql = $this->pdo->prepare('SELECT * FROM progress WHERE id_user = :user AND id_aula = :id');
            $sql->bindParam(":user", $_SESSION["id"]);
            $sql->bindParam(":id", $idAula);
            $sql->execute();
            $progress = $sql->fetch(PDO::FETCH_ASSOC);


            $aulas = "";

            if ($progress) {
                $aulas = $progress["assistido"];
            }

            return $aulas;

        } catch (Exception $e) {

        }
    }

/**
 * Retorna os dados de uma mídia, dado o id da aula.
 * 
 * @param int $id Id da aula
 * @return array|bool Dados da mídia ou falso se não encontrar
 * @throws PDOException
 */
    public function dadosAula($id)
    {

        try {
            $sql = $this->pdo->prepare("SELECT desc_midia, caminho_video FROM midias WHERE id_aula = :id");
            $sql->bindParam(":id", $id);
            $sql->execute();
            $midia = $sql->fetch(PDO::FETCH_ASSOC);

            if ($midia && $midia["caminho_video"]) {
                return ([
                    "success" => true,
                    "dados" => [
                        "desc_midia" => $midia['desc_midia'] ?? '',
                        "video" => $midia["caminho_video"]
                    ],


                ]);
            } else {
                return (["success" => false, "msg" => "Nenhuma mídia encontrada"]);
            }

        } catch (Exception $e) {
            $this->pdo->rollBack();

            return false;
        }
    }
/**
 * Retorna as áulas de um módulo, dado o id do módulo
 * 
 * @param int $id_modulo Id do módulo
 * @return array|bool Áulas do módulo ou falso se não encontrar
 * @throws PDOException
 */
    public function listaAulasDoModulo($id_modulo)
    {

        try {
            $sql = $this->pdo->prepare("SELECT * FROM aulas WHERE id_modulo = :id_modulo
            and excluido = 0");
            $sql->bindParam(":id_modulo", $id_modulo);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->pdo->rollBack();

            return false;
        }
    }



}
?>