<?php
require_once __DIR__ . "/../models/Certificados.php";

class CertificadosController
{

    private $certificado;

    public function __construct()
    {
        $this->certificado = new Certificados();
    }


    public function GerarNewCertificado()
    {

        require_once __DIR__ . "/../../control/authADM.php";

        try {
            $id_user = $_POST['idUser'];
            $Curso = $_POST['idCurso'];
            $idCurso = $_POST['idCurso'];



            $verifica = $this->certificado->obterCertificados($id_user, $Curso);

            if ($verifica['total'] >= 1) {

                echo json_encode(["EXISTE" => true]);
                return;
            }

            $retorno = $this->certificado->gerarCertificado($id_user, $Curso);

            if (!$retorno) {
                echo json_encode(["success" => false]);
                return;
            } else {

                $atualizar = $this->certificado->atualizarCurso($id_user, $idCurso);

            }

            echo json_encode(["success" => true]);
        } catch (Exception $ex) {
            echo json_encode("Erro", $ex->getMessage());
        }


    }

    public function PegaCertificados()
    {

        require_once __DIR__ . "/../../control/auth.php";

        if ($_SESSION['tipo'] == 1) {
            $idUser = $_SESSION['id'];

            $busca = $this->certificado->buscarCertificadosPorid($idUser);

            echo json_encode([
                "success" => true,
                "certificados" => $busca
            ]);

            return;
        }

        if ($_SESSION['tipo'] == 2) {


            try {

                $retorno = $this->certificado->buscartodosCertificados();

                echo json_encode([
                    "success" => true,
                    "certificados" => $retorno
                ]);

            } catch (Exception $ex) {

                echo json_encode([
                    "success" => false,
                    "erro" => $ex->getMessage()
                ]);

            }
        }
    }
}
?>