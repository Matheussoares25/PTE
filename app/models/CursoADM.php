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

    /**
     * Busca todos os cursos
     *
     * @return array
     */
    public function buscarCursosGeral()
    {
        $sql = $this->pdo->prepare("SELECT * FROM treinamentos");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um curso pela sua ID
     * 
     * @param int $id ID do curso
     * 
     * @return array Contendo o curso, relacionados e usuários
     */
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
        WHERE a.id_curso = :id AND a.status_curso  IN (0, 1)
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


    public function notificar($assunto, $menssagem, $recebe ){
        
        switch ($assunto){
            case 1:
                $assunto = "Curso";
                break;
            case 2:
                $assunto = "Certificado";
                break;
            case 3:
                $assunto = "Prova";
                break;
            case 4:
                $assunto = "Outros";
        }



        $sql = $this->pdo->prepare("INSERT INTO notificacoes (assunto, mensagem, id_recebe,visualizado,data_envio) VALUES (:assunto, :menssagem, :recebe, :visualizado, now())");
        $sql->bindParam(":assunto", $assunto);
        $sql->bindParam(":menssagem", $menssagem);
        $sql->bindParam(":recebe", $recebe);
        $sql->bindValue(":visualizado", 0);
        return $sql->execute();
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
    /**
     * Insere um módulo com suas respectivas áulas
     *
     * @param string $nomeCurso Nome do módulo
     * @param int $idCurso ID do curso
     * @param int $qtd Quantidade de áulas a ser criada
     *
     * @return bool True se a operação for bem sucedida, false caso contrário
     */
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

    /**
     * Retorna as informações de um módulo a partir do seu ID do curso
     *
     * @param int $idCurso ID do curso
     *
     * @return array Associative array com as informações do módulo
     */
    public function infoModulo($idCurso)
    {
        $sql = $this->pdo->prepare("
        SELECT 
            m.id,
            m.nome_modolu,
            m.id_curso
        FROM modulos m
        WHERE m.id_curso = :idCurso
    ");

        $sql->bindParam(":idCurso", $idCurso);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    public function dellModulo($idModulo)
    {
        try {
            $this->pdo->beginTransaction();

            $idModulo = $_POST['idModulo'];


            $sql = $this->pdo->prepare("DELETE alt
        FROM alternativas alt
        INNER JOIN questoes q ON alt.id_questao = q.id
        INNER JOIN aulas a ON q.id_prova = a.id
        WHERE a.id_modulo = :idModulo
    ");
            $sql->bindParam(":idModulo", $idModulo);
            $sql->execute();


            $sql = $this->pdo->prepare("DELETE q
        FROM questoes q
        INNER JOIN aulas a ON q.id_prova = a.id
        WHERE a.id_modulo = :idModulo
    ");
            $sql->bindParam(":idModulo", $idModulo);
            $sql->execute();


            $sql = $this->pdo->prepare("
        DELETE n
        FROM notas n
        INNER JOIN aulas a ON n.id_prova = a.id
        WHERE a.id_modulo = :idModulo
    ");
            $sql->bindParam(":idModulo", $idModulo);
            $sql->execute();


            $sql = $this->pdo->prepare("
        DELETE p
        FROM progress p
        INNER JOIN aulas a ON p.id_aula = a.id
        WHERE a.id_modulo = :idModulo
    ");
            $sql->bindParam(":idModulo", $idModulo);
            $sql->execute();


            $sql = $this->pdo->prepare("
        DELETE m
        FROM midias m
        INNER JOIN aulas a ON m.id_aula = a.id
        WHERE a.id_modulo = :idModulo
    ");
            $sql->bindParam(":idModulo", $idModulo);
            $sql->execute();


            $sql = $this->pdo->prepare("
        DELETE up
        FROM use_prova up
        INNER JOIN aulas a ON up.id_prova = a.id
        WHERE a.id_modulo = :idModulo
    ");
            $sql->bindParam(":idModulo", $idModulo);
            $sql->execute();


            $sql = $this->pdo->prepare("DELETE FROM aulas WHERE id_modulo = :idModulo");
            $sql->bindParam(":idModulo", $idModulo);
            $sql->execute();


            $sql = $this->pdo->prepare("DELETE FROM modulos WHERE id = :idModulo");
            $sql->bindParam(":idModulo", $idModulo);
            $sql->execute();

            $this->pdo->commit();

            return true;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    /**
     * Cria uma aula para o módulo informado
     *
     * @param int $idModulo ID do módulo
     *
     * @return bool True se a operação for bem sucedida, false caso contrário
     */
    public function criarAula($idModulo)
    {
        try {
            $sql = $this->pdo->prepare("INSERT INTO aulas (id_modulo, excluido,tipo ) VALUES (:id_modulo, 0, 1)");
            $sql->bindParam(":id_modulo", $idModulo);
            $sql->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

}
?>