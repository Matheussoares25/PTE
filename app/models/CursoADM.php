<?php
require_once __DIR__ . "/../../config/conn.php";

class CursoADM
{
    private $pdo;

    public function __construct()
    {
        $conexao = new Conexao();
        $this->pdo = $conexao->conn;
    }

    public function buscarCursosGeral()
    {
        $sql = $this->pdo->prepare("SELECT * FROM treinamentos");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        // Curso (agora direto como ARRAY)
        $sql = $this->pdo->prepare("SELECT * FROM treinamentos WHERE id = :id");
        $sql->bindParam(":id", $id);
        $sql->execute();
        $treinamentos = $sql->fetchAll(PDO::FETCH_ASSOC);

        // Relacionados
        $sql1 = $this->pdo->prepare("
        SELECT a.id_usuario, a.id_curso, a.status_curso, c.nome, b.email, a.matricula, a.data_curso  
        FROM use_treinamentos AS a 
        INNER JOIN usuarios AS b ON a.id_usuario = b.id 
        LEFT JOIN treinamentos AS c ON c.id = a.id_curso 
        WHERE a.id_curso = :id AND a.status_curso = 1
    ");
        $sql1->bindParam(":id", $id);
        $sql1->execute();
        $relacionados = $sql1->fetchAll(PDO::FETCH_ASSOC);

        // Usuários
        $sql2 = $this->pdo->prepare("
        SELECT id, email 
        FROM usuarios 
        WHERE ativos = 1 AND tipo = 1
    ");
        $sql2->execute();
        $usuarios = $sql2->fetchAll(PDO::FETCH_ASSOC);

        return [
            "treinamentos" => $treinamentos,
            "relacionados" => $relacionados,
            "usuarios" => $usuarios
        ];
    }

    public function cadastrarAoCurso($idCurso, $idUser)
    {

        try {
            $sql = $this->pdo->prepare("INSERT INTO use_treinamentos (id_curso, id_usuario,status_curso,data_curso) VALUES (:idCurso, :iduser, 1, now())");
            $sql->bindParam(":idCurso", $idCurso);
            $sql->bindParam(":iduser", $idUser);
            return $sql->execute();


        } catch (PDOException $e) {
            echo json_encode(["success" => false, "erro" => $e->getMessage()]);
        }
    }

    public function deletarMatircula($idUser, $idCurso)
    {

        try {
            $sql = $this->pdo->prepare("UPDATE use_treinamentos SET status_curso = 0 WHERE id_usuario = :u AND id_curso = :c");
            $sql->bindParam(":c", $idCurso);
            $sql->bindParam(":u", $idUser);
            return $sql->execute();

        } catch (Exception $e) {
            echo json_encode(["success" => false, "erro" => $e->getMessage()]);
        }
    }
    public function inserirModuloComAulas($nomeCurso, $idCurso, $qtd)
    {
        try {

            $this->pdo->beginTransaction();


            $sql = $this->pdo->prepare("
            INSERT INTO modulos (nome_modolu, id_curso) 
            VALUES (:nomeCurso, :idCurso)
        ");
            $sql->execute([
                ":nomeCurso" => $nomeCurso,
                ":idCurso" => $idCurso
            ]);

            $id_modulo = $this->pdo->lastInsertId();


            if ($qtd > 0) {

                $sqlAula = $this->pdo->prepare("
                INSERT INTO aulas (id_modulo, excluido, tipo) 
                VALUES (:id_modulo, 0, 1)
            ");

                for ($i = 0; $i < $qtd; $i++) {
                    $sqlAula->execute([
                        ":id_modulo" => $id_modulo
                    ]);
                }
            }

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {

            $this->pdo->rollBack();

            return false; // deixa o controller tratar
        }
    }

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