<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Banco</title>
  <link rel="stylesheet" href="../style/styleDbPHP.css">
</head>

<body>

  <div class="box">
    <h3>Gerenciar Banco</h3>

    <form method="post">

      <!--CAMPOS CONEXAO-->
      <label>Host:</label>
      <input type="text" name="host" placeholder="localhost" required>

      <label>Usuário:</label>
      <input type="text" name="user" placeholder="root" required>

      <label>Senha:</label>
      <input type="password" name="pass" placeholder="caso Exista">


      <!--SENHA DO SISTEMA-->
      <label>Senha do sistema</label>
      <input type="password" name="senhainserida" required>

      <button type="submit" name="gerar" class="gerar">
        Gerar Banco
      </button>

      <button type="submit" name="dropbanco" class="drop">
        Apagar Banco
      </button>
    </form>

    <div class="container">
      <div class="resultado">
        <?php

        ?>
      </div>
    </div>
  </div>

</body>

</html>

<?php


if (isset($_POST['gerar'])) {
  gerabanco();
}
if (isset($_POST['dropbanco'])) {
  dropbanco();
}

function tabelaExiste($pdo, $nome)
{
  $stmt = $pdo->query("SHOW TABLES LIKE '$nome'");
  return $stmt->rowCount() > 0;
}

function dropbanco()
{
  global $mensagem;

  $password = $_POST['senhainserida'] ?? '';
  $senhaSistema = "3747665522";

  if ($password != $senhaSistema) {
    echo " Senha incorreta";
    return;
  }

  $host = $_POST['host'];
  $user = $_POST['user'];
  $pass = $_POST['pass'];
  $banco = "pte";

  $conn = new PDO("mysql:host=$host;", $user, $pass);
  $conn->exec("DROP DATABASE IF EXISTS $banco");

  echo "Banco de dados apagado com sucesso.";
}
function gerabanco()
{

  $host = $_POST['host'];
  $user = $_POST['user'];
  $pass = $_POST['pass'];


  $password = $_POST['senhainserida'] ?? '';
  $senhaSistema = "3747665522";

  if ($password != $senhaSistema) {
    echo " Senha incorreta";
    return;
  }

  try {

    $pdo = new PDO("mysql:host=$host;", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS pte");
    $pdo->exec("USE pte");

    $criadas = 0;


    if (!tabelaExiste($pdo, 'treinamentos')) {
      $pdo->exec("CREATE TABLE treinamentos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(50),
                criado DATE
            )");
      $criadas++;
    }

    if (!tabelaExiste($pdo, 'usuarios')) {
      $pdo->exec("CREATE TABLE usuarios (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(250) NOT NULL,
                senha VARCHAR(260) NOT NULL,
                ativos INT,
                Foto MEDIUMBLOB,
                token VARCHAR(255),
                tipo VARCHAR(1) NOT NULL,
                nome VARCHAR(80) NOT NULL,
                acess TINYINT
            )");
      $criadas++;
    }

    if (!tabelaExiste($pdo, 'vagas')) {
      $pdo->exec("CREATE TABLE vagas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                titulo VARCHAR(80),
                conteudo VARCHAR(255),
                data_vaga DATETIME
            )");
      $criadas++;
    }


    if (!tabelaExiste($pdo, 'modulos')) {
      $pdo->exec("CREATE TABLE modulos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome_modolu VARCHAR(100),
                id_curso INT,
                FOREIGN KEY (id_curso) REFERENCES treinamentos(id)
            )");
      $criadas++;
    }

    if (!tabelaExiste($pdo, 'aulas')) {
      $pdo->exec("CREATE TABLE aulas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome_aula VARCHAR(100),
                id_modulo INT,
                excluido TINYINT,
                tipo TINYINT,
                FOREIGN KEY (id_modulo) REFERENCES modulos(id)
            )");
      $criadas++;
    }

    if (!tabelaExiste($pdo, 'questoes')) {
      $pdo->exec("CREATE TABLE questoes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                pergunta TEXT,
                id_prova INT,
                FOREIGN KEY (id_prova) REFERENCES aulas(id)
            )");
      $criadas++;
    }

    if (!tabelaExiste($pdo, 'alternativas')) {
      $pdo->exec("CREATE TABLE alternativas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_questao INT,
                texto TEXT,
                correta TINYINT,
                id_prova INT,
                FOREIGN KEY (id_questao) REFERENCES questoes(id),
                FOREIGN KEY (id_prova) REFERENCES aulas(id)
            )");
      $criadas++;
    }

    if (!tabelaExiste($pdo, 'midias')) {
      $pdo->exec("CREATE TABLE midias (
                id INT AUTO_INCREMENT PRIMARY KEY,
                desc_midia VARCHAR(100),
                id_aula INT,
                conteudo LONGBLOB,
                caminho_video VARCHAR(255),
                FOREIGN KEY (id_aula) REFERENCES aulas(id)
            )");
      $criadas++;
    }

    if (!tabelaExiste($pdo, 'candidaturas')) {
      $pdo->exec("CREATE TABLE candidaturas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_user INT,
                id_vaga INT,
                data_cand DATETIME,
                FOREIGN KEY (id_user) REFERENCES usuarios(id),
                FOREIGN KEY (id_vaga) REFERENCES vagas(id)
            )");
      $criadas++;
    }

    if (!tabelaExiste($pdo, 'certificado')) {
      $pdo->exec("CREATE TABLE certificado (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_user INT,
                emitido DATETIME,
                Curso VARCHAR(240),
                token VARCHAR(64),
                FOREIGN KEY (id_user) REFERENCES usuarios(id)
            )");
      $criadas++;
    }

    if (!tabelaExiste($pdo, 'notas')) {
      $pdo->exec("CREATE TABLE notas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_prova INT,
                id_aluno INT,
                nota INT,
                FOREIGN KEY (id_prova) REFERENCES aulas(id),
                FOREIGN KEY (id_aluno) REFERENCES usuarios(id)
            )");
      $criadas++;
    }

    if (!tabelaExiste($pdo, 'noticias')) {
      $pdo->exec("CREATE TABLE noticias (
                id INT AUTO_INCREMENT PRIMARY KEY,
                titulo VARCHAR(80),
                conteudo VARCHAR(255),
                data_noticia DATETIME,
                vaga INT
            )");
      $criadas++;
    }

    if (!tabelaExiste($pdo, 'progress')) {
      $pdo->exec("CREATE TABLE progress (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_aula INT,
                id_user INT,
                assistido INT,
                data_assistida DATETIME,
                UNIQUE (id_aula,id_user),
                FOREIGN KEY (id_aula) REFERENCES aulas(id),
                FOREIGN KEY (id_user) REFERENCES usuarios(id)
            )");
      $criadas++;
    }

    if (!tabelaExiste($pdo, 'reports')) {
      $pdo->exec("CREATE TABLE reports (
                id INT AUTO_INCREMENT PRIMARY KEY,
                reclamacao VARCHAR(255),
                id_usuario INT,
                data DATETIME,
                FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
            )");
      $criadas++;
    }

    if (!tabelaExiste($pdo, 'use_prova')) {
      $pdo->exec("CREATE TABLE use_prova (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_user INT,
                id_prova INT,
                acertos VARCHAR(50),
                data_conclusao DATETIME,
                data_inicio DATETIME,
                aprovado INT,
                porcentagem INT,
                qtd_questoes INT,
                nota INT,
                FOREIGN KEY (id_user) REFERENCES usuarios(id),
                FOREIGN KEY (id_prova) REFERENCES aulas(id)
            )");
      $criadas++;
    }

    if (!tabelaExiste($pdo, 'use_treinamentos')) {
      $pdo->exec("CREATE TABLE use_treinamentos (
                matricula INT AUTO_INCREMENT PRIMARY KEY,
                id_usuario INT,
                id_curso INT,
                status_curso VARCHAR(1),
                data_curso DATETIME DEFAULT CURRENT_TIMESTAMP,
                data_fim DATETIME,
                modulo INT,
                FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
                FOREIGN KEY (id_curso) REFERENCES treinamentos(id)
            )");
      $criadas++;
    }


    $senha = '$2y$10$apAYOD4QD0343NhX2ooqyOQeY2tBUfRrTCGRW85IZYWqSJbjerEf6';

    $inserts = $pdo->exec("INSERT IGNORE INTO usuarios 
        (id, email, senha, ativos, Foto, token, tipo, nome, acess) VALUES
        (1,'pte@pte', '$senha', 1, NULL, '', '2', 'Matheus adm', 1),
        (2, 'funcionario@email.com', '$senha', 1, NULL, '', '1', 'funcionario', 1)");

    if ($criadas == 0) {
      echo "<br> Banco de dados ja Criado!";
      echo "<br> $criadas tabelas criadas";
      echo "<br> $inserts usuários inseridos";
      

    } else {
      echo "<br> Banco de dados criado!";
      echo "<br> $criadas tabelas criadas";
      echo "<br> $inserts usuários inseridos";
    }


  } catch (PDOException $e) {


    if ($e->getCode() == 1045) {
      echo "<br> Host do banco ou Senha incorreta";
    } else {
      echo "<br> Erro ao criar o banco de dados: " . $e->getMessage();
    }
  }
}
?>