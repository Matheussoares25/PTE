<?php
require_once __DIR__ . "/../models/Usuario.php";

class UsuarioController
{

    private $usuario;

    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    public function cadastrar()
    {

        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["success" => false, "mensagem" => "Método inválido"]);
            return;
        }

        try {

            $email = $_POST['email'] ?? '';
            $nome = $_POST['nome'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $foto = $_FILES['foto'] ?? null;

            if (empty($email) || empty($senha)) {
                echo json_encode(["success" => false, "mensagem" => "Email e senha obrigatórios"]);
                return;
            }

            if (!$foto || $foto['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(["success" => false, "mensagem" => "Foto é obrigatória"]);
                return;
            }

            if ($this->usuario->existeEmail($email)) {
                echo json_encode(["Existe" => true]);
                return;
            }

            $conteudo = file_get_contents($foto['tmp_name']);
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $ok = $this->usuario->inserir($email, $nome, $senhaHash, $conteudo);

            if ($ok) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false]);
            }

        } catch (PDOException $e) {
            echo json_encode([
                "success" => false,
                "erro" => $e->getMessage()
            ]);
        }
    }

    public function login()
    {
        session_start();
        session_regenerate_id(true);

        header('Content-Type: application/json; charset=utf-8');


        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["success" => false, "mensagem" => "Método inválido"]);
            return;
        }

        try {
            $email = $_POST["email"];
            $senha = $_POST["senha"];

            $res = $this->usuario->buscaEmail($email);

            if (!$res) {
                echo json_encode(["NAOEXISTE" => true, "message" => "Usuario nao cadastrado"]);
                return;
            }

            if (!password_verify($senha, $res['senha'])) {
                echo json_encode(['serrada' => true]);
                return;
            }

            $_SESSION['id'] = $res['id'];
            $_SESSION['tipo'] = (int) $res['tipo'];
            $_SESSION['email'] = $res['email'];

            if (!empty($res['Foto'])) {
                $_SESSION['foto'] = "data:image/jpeg;base64," . base64_encode($res['Foto']);
            }



            $token = bin2hex(random_bytes(32));

            $_SESSION['token'] = $token;

            $this->usuario->atualizarToken($res['id'], $token);

            $dados = [
                "success" => true,
                "token" => $token,
                "id" => $res["id"],
                "tipo" => $res["tipo"],
                "email" => $res["email"],
                "foto" => $res["Foto"] ?? null

            ];
 
         
            if ($res['acess'] == 0) {
                $this->usuario->atualizarAcesso($res['id']);
            }

            echo json_encode($dados);

        } catch (PDOException $e) {
            echo json_encode([
                "success" => false,
                "erro" => $e->getMessage()
            ]);


        }
    }

    public function verificarAcesso()
    {

        $email = $_POST["email"];

        $res = $this->usuario->buscaEmail($email);

        if (!$res) {
            echo json_encode(["NAOEXISTE" => true, "message" => "Usuario nao cadastrado"]);
            return;
        }

        $dados = $this->usuario->buscaPorAcesso($email);

        if ($dados["acess"] == 0) {
            echo json_encode(["PACESS" => true]);
            return;
        } else {
            echo json_encode(["PACESS" => false]);

        }
    }

    public function chekarSessao()
    {
        session_start();
        header('Content-Type: application/json; charset=utf-8');

        $idUser = $_SESSION['id'] ?? null;
        $token = $_SESSION['token'] ?? null;

        $res = $this->usuario->controleDeSessao($idUser, $token);
        if ($res['EXPIRADO'] == true) {

        }
        echo json_encode($res);
    }


}

?>