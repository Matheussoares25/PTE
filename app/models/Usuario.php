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

    /**
     * Verifica se já existe um usuario com o email especificado.
     *
     * @param string $email O email do usuario a ser verificado.
     *
     * @return array|null Retorna o id do usuario se encontrado, ou null caso n o encontrado.
     */
    public function existeEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Insere um novo usuario no banco de dados
     *
     * @param string $email Email do usuario
     * @param string $nome Nome do usuario
     * @param string $senhaHash Hash da senha do usuario
     * @param string $foto Foto do usuario
     *
     * @return bool Retorna true se a inser o for bem sucedida, false caso contr rio
     */
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

    /**
     * Busca o usuario com o email especificado.
     *
     * @param string $email O email do usuario a ser buscado.
     *
     * @return array Contendo o id, senha, tipo, acesso e email do usuario, caso encontrado.
     */
    public function buscaEmail($email)
    {
        $stm = $this->pdo->prepare('SELECT id, senha, tipo, acess, email, Foto FROM usuarios WHERE email = :email');

        $stm->bindParam(':email', $email);
        $stm->execute();

        $res = $stm->fetch(PDO::FETCH_ASSOC);

        if ($res && !empty($res['Foto'])) {
            $res['Foto'] = "data:image/jpeg;base64," . base64_encode($res['Foto']);
        }

        return $res;
    }

    /**
     * Busca o acesso do usuario com o email especificado.
     *
     * @param string $email O email do usuario a ser buscado.
     *
     * @return array Contendo o acesso do usuario, caso encontrado.
     */
    public function buscaPorAcesso($email)
    {
        $sql = $this->pdo->prepare('SELECT acess from usuarios where email = :email');
        $sql->bindParam(":email", $email);
        $sql->execute();
        return $sql->fetch();
    }

    /**
     * Atualiza o token do usuario com o id especificado.
     *
     * @param int $id O id do usuario a ser atualizado.
     * @param string $token O novo token a ser atribu do.
     *
     * @return void
     */
    public function atualizarToken($id, $token)
    {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET token = :token WHERE id = :id");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    /**
     * Atualiza o acesso do usuario para 1, apenas se o acesso atual for 0.
     * Isso serve para controlar se o usuario j  fez login ou n o.
     * @param int $id O id do usuario a ser atualizado.
     */
    public function atualizarAcesso($id)
    {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET acess = 1 WHERE id = :id AND acess = 0");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    /**
     * Verifica se o token atual do usuario com o id especificado 
     *   ainda   v lido.
     *
     * @param int $idUsuario O id do usuario a ser verificado.
     * @param string $tokenAtual O token atual do usuario.
     *
     * @return array Um array com uma chave 'EXPIRADO' e valor booleano.
     * Se o token atual do usuario for expirado, o valor   true, caso contr rio, false.
     */
    public function controleDeSessao($idUsuario, $tokenAtual)
    {
        $stmt = $this->pdo->prepare('SELECT token FROM usuarios WHERE id = :idUser');
        $stmt->bindParam(':idUser', $idUsuario);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$res || $res['token'] !== $tokenAtual) {
            return ['EXPIRADO' => true];
        }

        return ['EXPIRADO' => false];
    }

}