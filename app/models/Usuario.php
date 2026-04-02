<?php
require_once __DIR__ . "/../../config/conn.php";
class Usuario
{

    private $pdo;

    public function __construct()
    {
        $conexao = new Conexao();
        $this->pdo = $conexao->conn;
    }

    public function existeEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function inserir($email, $nome, $senhaHash, $foto)
    {
        $query = $this->pdo->prepare("INSERT INTO usuarios 
            (Email, senha, ativos, Foto, tipo, nome, acess) 
            VALUES (:email, :senha, 1, :foto, 1, :nome, 0)
        ");

        $query->bindValue(':email', $email);
        $query->bindValue(':nome', $nome);
        $query->bindValue(':senha', $senhaHash);
        $query->bindValue(':foto', $foto, PDO::PARAM_LOB);

        return $query->execute();
    }

    public function buscaEmail($email)
    {
        $stm = $this->pdo->prepare('SELECT id,senha, tipo,acess, email FROM usuarios WHERE email = :email');
        $stm->bindParam(':email', $email);
        $stm->execute();
        return $stm->fetch();
    }

    public function atualizarToken($id, $token)
    {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET token = :token WHERE id = :id");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function atualizarAcesso($id)
    {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET acess = 1 WHERE id = :id AND acess = 0");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

}