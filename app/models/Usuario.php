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

    public function buscaporId($id)
    {
        $stm = $this->pdo->prepare('SELECT id, senha, tipo, acess, email,nome, Foto FROM usuarios WHERE id = :id');

        $stm->bindParam(':id', $id);
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

    public function reportar($reclamacao, $usuario)
    {

        $sql = $this->pdo->prepare('INSERT INTO reports (reclamacao, id_usuario,data) VALUES (:reclamacao, :idUsuario, NOW())');
        $sql->bindParam(":reclamacao", $reclamacao);
        $sql->bindParam(":idUsuario", $usuario);
        return $sql->execute();
    }

    public function editrPerfil($nome, $senha, $id)
    {
        $campos = [];
        $params = [":id" => $id];

        if (!empty($nome)) {
            $campos[] = "nome = :nome";
            $params[":nome"] = $nome;
        }

        if (!empty($senha)) {
            $campos[] = "senha = :senha";
            $params[":senha"] = $senha;
        }


        if (empty($campos)) {
            return false;
        }

        $sql = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id = :id";

        $stm = $this->pdo->prepare($sql);
        return $stm->execute($params);
    }

    public function alterarTipo($id)
    {

        $sql = $this->pdo->prepare("UPDATE usuarios SET tipo = CASE WHEN tipo = 2 THEN 1 ELSE 2 END
        WHERE id = :id ");
        $sql->bindParam(":id", $id);
        return $sql->execute();
    }

    public function BloquearOUDesbloquearAcesso($id)
    {
        $sql = $this->pdo->prepare("UPDATE usuarios SET acess = CASE WHEN acess = 1 THEN 2 ELSE 1 END WHERE id = :id");
        $sql->bindParam(":id", $id);
        return $sql->execute();
    }

    public function deletarUsuario($id)
    {
        try {
            $this->pdo->beginTransaction();

            $this->pdo->prepare("DELETE FROM candidaturas WHERE id_user = ?")->execute([$id]);
            $this->pdo->prepare("DELETE FROM certificado WHERE id_user = ?")->execute([$id]);
            $this->pdo->prepare("DELETE FROM notas WHERE id_aluno = ?")->execute([$id]);
            $this->pdo->prepare("DELETE FROM progress WHERE id_user = ?")->execute([$id]);
            $this->pdo->prepare("DELETE FROM use_prova WHERE id_user = ?")->execute([$id]);
            $this->pdo->prepare("DELETE FROM use_treinamentos WHERE id_usuario = ?")->execute([$id]);
            $this->pdo->prepare("DELETE FROM reports WHERE id_usuario = ?")->execute([$id]);

            $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ? AND tipo = 1");
            $stmt->execute([$id]);

            $this->pdo->commit();

            return $stmt->rowCount() > 0;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function buscaNotificaçoes($id_user){

        $sql = $this->pdo->prepare("SELECT * FROM notificacoes WHERE id_recebe = :idUser ORDER BY data_envio DESC LIMIT 5");
        $sql->bindParam(":idUser",$id_user);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contaNotificaçoes($id_user){

        $sql = $this->pdo->prepare("SELECT COUNT(*) as total FROM notificacoes WHERE id_recebe = :idUser and visualizado = 0");
        $sql->bindParam(":idUser",$id_user);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function visualizarNotificaçoes($id_user){

        $sql = $this->pdo->prepare("UPDATE notificacoes SET visualizado = 1 WHERE id_recebe = :idUser order by data_envio DESC");
        $sql->bindParam(":idUser",$id_user);
        return $sql->execute();
    }

}