<?php

include("conn.php");
include("auth.php");



try {
  $con = new Conexao();
  $pdo = $con->conn;

  $id = $_GET["id"] ?? null;

  $sql = $pdo->prepare("SELECT * FROM modulos WHERE id_curso = :id");
  $sql->bindParam(":id", $id);
  $sql->execute();
  $treinamentos = $sql->fetchAll(PDO::FETCH_ASSOC);

  $sql = $pdo->prepare("SELECT nome FROM treinamentos WHERE id = :id");
  $sql->bindParam(":id", $id);
  $sql->execute();
  $nameT = $sql->fetch(PDO::FETCH_ASSOC);


  $sql = $pdo->prepare("SELECT * FROM modulos
    WHERE id_curso = $id
    ORDER BY id
");

  $sql->execute();
  $modulos = $sql->fetchAll(PDO::FETCH_ASSOC);

  var_dump($modulos);


} catch (Exception $e) {
  echo json_encode(["sucesso" => false, "erro" => $e->getMessage()]);
}
?>

<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <title>Treinamento</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  < <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    < <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


      <style>
        body {
          margin: 0;
          height: 100vh;
          background: #f5f6f8;
          overflow: hidden;
        }

        .layout {
          display: grid;
          grid-template-columns: 320px 1fr;
          height: 100vh;
        }


        .sidebar {
          background: #ffffff;
          border-right: 1px solid #e0e0e0;
          padding: 24px;
          overflow-y: auto;
        }

        .sidebar h4 {
          font-weight: 600;
          margin-bottom: 24px;
        }

        .lista-aulas {
          list-style: none;
          padding: 0;
          margin: 0;
        }

        .lista-aulas li {
          display: flex;
          align-items: center;
          gap: 12px;
          padding: 12px 8px;
          border-radius: 8px;
          cursor: pointer;
          transition: background .2s;
        }

        .lista-aulas li:hover {
          background: #f0f2f5;
        }

        .lista-aulas li.active {
          background: #e7f1ff;
          font-weight: 500;
        }

        .lista-aulas i {
          font-size: 18px;
          color: #555;
        }


        .player {
          display: flex;
          flex-direction: column;
          background: #f5f6f8;
          justify-content: center;
        }

        .video-container {
          position: relative;
          height: 55vh;
          width: 100%;
          background: #000;
          display: flex;
          align-items: center;
          justify-content: center;
          border-radius: 0;
        }

        .video-container video {
          width: 100%;
          height: 100%;
          object-fit: contain;
          background: #000;
        }

        .video-placeholder {
          color: #fff;
          text-align: center;
        }

        .video-placeholder i {
          font-size: 80px;
          margin-bottom: 16px;
        }

        /* CONTROLES (visual) */
        .controls {
          background: #ffffff;
          /* controles em fundo claro */
          padding: 12px 20px;
          display: flex;
          align-items: center;
          gap: 16px;
          color: #333;
          border-top: 1px solid #e0e0e0;
        }

        .progress-bar-custom {
          flex: 1;
          height: 4px;
          background: #333;
          border-radius: 2px;
          position: relative;
        }

        .progress-bar-custom span {
          position: absolute;
          left: 0;
          top: 0;
          height: 100%;
          width: 30%;
          background: #e53935;
          border-radius: 2px;
        }

        .controls i {
          cursor: pointer;
          font-size: 18px;
          color: #333;
        }

        @media (max-width: 768px) {
          .layout {
            grid-template-columns: 1fr;
          }

          .sidebar {
            display: none;
          }
        }

        .aula-descricao {
          background: #ffffff;
          color: #333;
          padding: 16px 24px;
          border-top: 1px solid #e0e0e0;
        }

        .aula-descricao h5 {
          margin-bottom: 8px;
          font-weight: 600;
        }

        .aula-descricao p {
          margin: 0;
          font-size: 14px;
          line-height: 1.5;
        }
      </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg shadow-sm" style="background-color:#4682B4;">
    <div class="container">

      <a class="navbar-brand fw-bold text-white" href="noticias.html" style="font-size:22px;">
        <i class="bi bi-mortarboard-fill me-1"></i> PTE
      </a>

      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <div class="navbar-nav align-items-lg-center gap-lg-3">

          <a class="nav-link text-white fw-semibold" href="treinamentos.html">Treinamentos</a>
          <a class="nav-link text-white fw-semibold btnadm" href="#">Desempenho</a>
          <a class="nav-link text-white fw-semibold" href="ranking.html">Ranking</a>
          <a class="nav-link text-white fw-semibold" href="noticias.html">Notícias</a>
          <a class="nav-link text-white fw-semibold" href="#">Certificados</a>

          <button type="button" onclick="oflog()" class="btn btn-light text-primary fw-semibold px-3 ms-lg-3"
            style="border-radius: 8px;">
            <i class="bi bi-box-arrow-right me-1"></i> Sair
          </button>

        </div>
      </div>

    </div>
  </nav>

  <div class="layout">
    <aside class="sidebar">
      <h4>Treinamento: <strong id="nameCurso"> <strong>
            <?= htmlspecialchars($nameT['nome']) ?>
          </strong> </strong></h4>

      <ul class="lista-aulas">
        <?php if (!empty($modulos)): ?>
          <?php foreach ($modulos as $modulo): ?>
            <li onclick="abrirModulo(<?= $modulo['id'] ?>)">
              <i class="fa-solid fa-folder"></i>
              <span><?= htmlspecialchars($modulo['nome_modolu']) ?></span>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>Nenhum módulo cadastrado</li>
        <?php endif; ?>
      </ul>

      <ul id="AulasMod">

      </ul>
    </aside>

    <main class="player" id="video">
      <div class="video-container">

        <div class="video-placeholder">
          <i class="fa-solid fa-play-circle"></i>
          <p>Clique para iniciar o vídeo</p>
        </div>
      </div>

      <div class="aula-descricao">
        <h5>Descrição da aula</h5>
        <p id="descAula">

        </p>
      </div>

      <div class="controls">
        <div class="progress-bar-custom">
          <span></span>
        </div>
        <i class="fa-solid fa-backward-step"></i>
        <i class="fa-solid fa-pause"></i>
        <i class="fa-solid fa-forward-step"></i>
        <i class="fa-solid fa-volume-high"></i>
      </div>

    </main>

    <main id="prova">
      <ul id="ListQuestion">
        <ul id="ListAlt"></ul>


      </ul>

    </main>



  </div>



</body>

</html>
<script src="js/functions.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
  async function abrirModulo(id) {
    let ul = document.getElementById("AulasMod");
    ul.style.display = "block";
    ul.innerHTML = "<li>Carregando aulas...</li>";

    let dados = new FormData();
    dados.append("idModulo", id);

    try {
      const resposta = await fetch("listarAulas.php", {
        method: "POST",
        body: dados
      });

      const resp = await resposta.json();

      ul.innerHTML = "";

      if (!resp.aulas || resp.aulas.length === 0) {
        ul.innerHTML = "<li>Nenhuma aula encontrada</li>";
        return;
      }

      resp.aulas.forEach(function (aula) {
        let func;

        if (aula.tipo == 1) {
          func = "abrirAula";
        } else if (aula.tipo == 2) {
          func = "abrirProva";
        } else {
          func = "abrirAula";
        }

        ul.innerHTML +=
          "<li style='padding-left:25px;' onclick=\"" + func + "('" + aula.id + "')\">" +
          "<i class='fa-solid fa-play-circle text-danger'></i> " +
          "<span>" + aula.nome_aula + "</span>" +
          "</li>";
      });

    } catch (error) {
      ul.innerHTML = "<li>Erro ao carregar aulas</li>";
      console.error(error);
    }
  }


  async function abrirAula(id) {

    let formdata = new FormData();
    formdata.append("idAula", id);

    const res = await fetch("dadosAula.php",
      {
        method: "POST",
        body: formdata,
        credentials: "include"
      });
    const dados = await res.json();

    if (dados && dados.dados && dados.dados.desc_midia) {
      document.getElementById("descAula").innerText = dados.dados.desc_midia;
    }

    if (dados.sucesso && dados.dados && dados.dados.conteudo) {
      document.querySelector(".video-container").innerHTML = `
            <video controls autoplay style="width:100%; height:100%;">
                <source src="${dados.dados.conteudo}" type="video/mp4">
                Seu navegador não suporta vídeo.
            </video>
        `;
    }
    console.log(dados);
  };


  async function abrirProva(id) {
    document.getElementById("prova").style.display = "block";
    document.getElementById("video").style.display = "none";


    console.log(id);

    let form = new FormData();
    form.append("idProva", id);

    const res = await fetch("questoes.php", {
      method: "POST",
      body: form,
      credentials: "include"
    });

    const resposta = await res.json();

    const lista = document.getElementById("ListQuestion");
    lista.innerHTML = "";

    resposta.Questoes.forEach(function (questao) {

      let html = `
  <li class="list-group-item mb-3">
    <h5 class="mb-3">${questao.pergunta}</h5>
`;

      questao.alternativas.forEach(function (alt) {
        html += `
  
    <div class="form-check mb-2">
      <input 
        class="form-check-input"
        type="radio"
        name="q_${questao.id}"
        value="${alt.id}"
        id="alt_${alt.id}"
      >
      <label class="form-check-label" for="alt_${alt.id}">
        ${alt.texto}
      </label>
    </div>
  `;
      });

      html += `
  </li>
`;

      lista.innerHTML += html;

    });
  }


</script>