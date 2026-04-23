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

        if ($dados["acess"] == 2) {
            echo json_encode(["BLOQUEADO" => true]);
            return;
        }

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
        $email = $_SESSION['email'] ?? null;

        $chekinDeAcesso = $this->usuario->buscaPorAcesso($email);
        if ($chekinDeAcesso['acess'] == 2) {
            echo json_encode(['BlOQUEADO' => true]);
            return;
        }




        $res = $this->usuario->controleDeSessao($idUser, $token);
        if ($res['EXPIRADO'] == true) {

        }
        echo json_encode($res);
    }

    public function abrirReclamacao()
    {

        try {
            session_start();
            $reclamacao = $_POST['problema'] ?? null;
            $usuario = $_SESSION['id'] ?? null;

            $res = $this->usuario->reportar($reclamacao, $usuario);

            if ($res) {
                echo json_encode(['success' => true]);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function abrirPerfil()
    {

        try {
            session_start();
            $id = $_POST['id'] ?? null;
            if ($id == null) {
                $id = $_SESSION['id'] ?? null;
            }
            $res = $this->usuario->buscaporId($id);



            echo json_encode($res);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function editarPefil()
    {
        try {

            session_start();
            $id = $_POST['id'] ?? null;
            if ($id == null) {
                $id = $_SESSION['id'] ?? null;
            }



            $senhaAtual = $_POST['senha'] ?? null;
            $nome = $_POST['nome'] ?? null;
            $senhaNova = $_POST['senhaNova'] ?? null;


            $resUsuario = $this->usuario->buscaporId($id);


            if (!empty($senhaNova)) {

                if (empty($senhaAtual)) {
                    echo json_encode(['erro' => 'Digite a senha atual']);
                    return;
                }

                if (!password_verify($senhaAtual, $resUsuario['senha'])) {
                    echo json_encode(['serrada' => true]);
                    return;
                }


                $senhaNova = password_hash($senhaNova, PASSWORD_DEFAULT);
            }

            $execute = $this->usuario->editrPerfil($nome, $senhaNova, $id);

            echo json_encode(['success' => $execute]);

        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function alterarTipoDoUsuario()
    {

        try {
            $id = $_POST['id'] ?? null;
            $res = $this->usuario->alterarTipo($id);
            echo json_encode(['success' => $res]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    public function alterarTipoDeAcesso()
    {
        try {
            $id = $_POST['id'] ?? null;
            $res = $this->usuario->BloquearOUDesbloquearAcesso($id);
            echo json_encode(['success' => $res]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function deletarUsuarioDaPlataforma()
    {

        try {
            $id = $_POST['id'] ?? null;
            $usuario = $this->usuario->deletarUsuario($id);
            echo json_encode(['success' => $usuario]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }


}



?>