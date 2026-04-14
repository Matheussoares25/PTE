<?php
require_once __DIR__ . "/../../config/conn.php";

class adm
{

    private $pdo;

    public function __construct()
    {
        $conexao = new conexao();
        $this->pdo = $conexao->conn;
    }

    /**
     * Checa se a senha e o email esta ao cadastrado no banco de dados
     * e se o tipo do usuario 2 (administrador)
     * 
     * @param string $senha Senha do usuario
     * @param string $email Email do usuario
     * 
     * @return array Retorna um array com a chave "NAOEXISTE", "SENHAERRADA", "NEGADO" ou "LIBERADO"
     */
    public function chekin($senha, $email)
    {
        $sql = $this->pdo->prepare("
        SELECT id, senha, tipo, acess 
        FROM usuarios 
        WHERE email = :email
    ");

        $sql->bindParam(':email', $email);
        $sql->execute();

        $res = $sql->fetch(PDO::FETCH_ASSOC);

        if (!$res) {
            return ["NAOEXISTE" => true];
        }


        if (!password_verify($senha, $res['senha'])) {
            return ["SENHAERRADA" => true];
        }


        if ($res['tipo'] != 2) {
            return ["NEGADO" => true];
        }

        return ["LIBERADO" => true];
    }

}