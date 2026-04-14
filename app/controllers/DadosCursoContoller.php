<?php

require_once __DIR__ . "/../models/DadosRelacionadoAcurso.php";

class DadosCursoContoller
{
    private $cursoContoller;
    public function __construct()
    {
        $this->cursoContoller = new DadosDoCurso();
    }

    public function buscadorQuestoes()
    {

        session_start();

        $tipoUser = $_SESSION["tipo"];
        if ($tipoUser == 2) {
            $idProva = $_POST['idProva'] ?? "";

            $curso = $this->cursoContoller->buscarQuestoes($idProva);

            echo json_encode($curso);
        }
        if ($tipoUser == 1) {
            $idProva = $_POST["idProva"] ?? "";

            $curso = $this->cursoContoller->buscarQuestoesComum($idProva);

            echo json_encode($curso);
        }
    }

    public function cadastrarCurso()
    {



        try {
            $curso = $_POST["nome"] ?? "";

            $res = $this->cursoContoller->verificarSeCursoExiste($curso);
            if ($res) {
                echo json_encode(["Existe" => true]);
                exit;
            }

            $res = $this->cursoContoller->cadastrarCurso($curso);
            if ($res) {
                echo json_encode(["success" => true]);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(["success" => false]);
        }
    }

    public function cadastrarumaAula()
    {
        try {
            $idAula = intval($_POST["idAula"] ?? 0);
            $idModulo = intval($_POST["idModulo"] ?? 0);
            $nomeAula = trim($_POST["nomeAula"] ?? '');
            $desc = $_POST['desc'] ?? '';
            $video = $_FILES['video'] ?? null;
            
            $res =$this->cursoContoller->cadastarAula($idAula, $idModulo, $nomeAula, $desc, $video);
           echo json_encode($res);
        }catch (Exception $e) {
            echo json_encode(['success'=> false]);
        }
    }
}

?>