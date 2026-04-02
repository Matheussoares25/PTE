<?php
require_once __DIR__ . "/../models/Curso.php";
require_once __DIR__ . "/../models/CursoADM.php";

class CursoController
{
    private $curso;
    private $admcurso;

    public function __construct()
    {
        $this->curso = new Curso();
        $this->admcurso = new CursoADM();
    }

    public function buscarCursosDoAluno()
    {
        require_once __DIR__ . "/../../control/auth.php";

        header('Content-Type: application/json; charset=utf-8');

        $idUser = $_SESSION["id"];

        $dados = $this->curso->buscarTreinamentosDoUsuario($idUser);

        echo json_encode([
            "success" => true,
            "dados" => $dados
        ]);
    }

    public function buscarCursosConcluidosDoAluno()
    {
        require_once __DIR__ . "/../../control/auth.php";

        header('Content-Type: application/json; charset=utf-8');

        $idUser = $_SESSION["id"];

        $dados = $this->curso->buscarTreinamentosConcluidosDoUsuario($idUser);

        echo json_encode([
            "success" => true,
            "dados" => $dados
        ]);
    }

    public function buscarCursosGeralADM()
    {
        require_once __DIR__ . "/../../control/authADM.php";

        header('Content-Type: application/json; charset=utf-8');

        $dados = $this->admcurso->buscarCursosGeral();

        echo json_encode((array) $dados);
    }

    public function buscaPorId()
    {

        require_once __DIR__ . '/../../control/authADM.php';

        header('Content-Type: application/json; charset=utf-8');

        $id = $_POST['id'];

        $dados = $this->admcurso->buscarPorId($id);

        echo json_encode($dados);
    }
    public function cadastrarAoCurso()
    {
        require_once __DIR__ . '/../../control/authADM.php';

        header('Content-Type: application/json; charset=utf-8');

        $idCurso = $_POST['idcurso'];
        $idUser = $_POST['usuario'];

        $dados = $this->admcurso->cadastrarAoCurso($idCurso, $idUser);

        echo json_encode([
            "success" => true,
            "dados" => $dados
        ]);
    }
    public function deletarMatircula()
    {
        require_once __DIR__ . '/../../control/authADM.php';

        header('Content-Type: application/json; charset=utf-8');

        $idUser = $_POST['id_usuario'];
        $idCurso = $_POST['id_curso'];

        $dados = $this->admcurso->deletarMatircula($idUser, $idCurso);

        echo json_encode([
            "success" => true,
            "dados" => $dados
        ]);
    }

    public function listarCursosComEstrutura()
    {
        require_once __DIR__ . "/../../control/authADM.php";

        header('Content-Type: application/json; charset=utf-8');

        try {
            $dados = $this->curso->listarCursosComModulosEAulas();

            echo json_encode([
                "success" => true,
                "cursos" => array_values($dados)
            ]);

        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "erro" => $e->getMessage()
            ]);
        }
    }

    public function inserirModulo()
    {
        require_once __DIR__ . "/../../control/authADM.php";

        header('Content-Type: application/json; charset=utf-8');

        $nome = $_POST['nome'] ?? '';
        $idCurso = $_POST['idCurso'] ?? null;
        $qtd = intval($_POST['qtd'] ?? 0);

        if (!$nome || !$idCurso) {
            echo json_encode(["success" => false, "erro" => "Dados inválidos"]);
            return;
        }

        $ok = $this->admcurso->inserirModuloComAulas($nome, $idCurso, $qtd);

        if ($ok) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false]);
        }
    }
}