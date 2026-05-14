<?php

include("../config/conn.php");
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

  $sql = $pdo->prepare("SELECT * FROM use_prova where id_user = :id and id_prova ");
  $sql->bindParam(":id", $_SESSION["id"]);
  $sql->execute();
  $provas = $sql->fetchAll(PDO::FETCH_ASSOC);

  $qtdProvas = count($provas);


  $sql = $pdo->prepare("SELECT status_curso FROM use_treinamentos where id_usuario = :iduser and id_curso = :idCurso");
  $sql->bindParam(":iduser", $_SESSION["id"], PDO::PARAM_INT);
  $sql->bindParam(":idCurso", $id, PDO::PARAM_INT);
  $sql->execute();
  $treinamentos = $sql->fetch(PDO::FETCH_ASSOC);


  $cursoConcluido = false;

  if (
    !empty($treinamentos) &&
    $treinamentos["status_curso"] == 2
  ) {
    $cursoConcluido = true;
  }










} catch (Exception $e) {
  echo json_encode(["sucesso" => false, "erro" => $e->getMessage()]);
}
?>

<html lang="pt-BR">
<script src="../js/functions.js"></script>

<head>
  <meta charset="UTF-8" />
  <title>Treinamento</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


<style>
  body {
    margin: 0;
    min-height: 100vh;
    background: linear-gradient(135deg, #eef3f9 0%, #f8fafc 100%);
    font-family: "Inter", "Segoe UI", sans-serif;
    color: #1f2937;
    overflow: hidden;
  }

  /* ===== LAYOUT ===== */
  .layout {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 18px;
    padding: 18px;
    height: calc(100vh - 72px);
    background: #f4f7fb;
  }

  /* ===== LAYOUT ===== */
.layout {
  display: grid;
  grid-template-columns: 380px 1fr;
  gap: 18px;
  padding: 18px;
  height: calc(100vh - 72px);
  background: #f4f7fb;
}

/* ===== SIDEBAR ===== */
.sidebar {
  background: #ffffff;
  border-radius: 20px;
  padding: 18px;
  overflow-y: auto;
  border: 1px solid #e5e7eb;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
  height: 100vh;
}

/* SCROLL */
.sidebar::-webkit-scrollbar {
  width: 7px;
}

.sidebar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 999px;
}

/* TITULO */
.sidebar h4 {
  font-size: 19px;
  margin-bottom: 20px;
  line-height: 1.5;
  font-weight: 700;
  color: #1e293b;
}

.sidebar h4 strong {
  color: #2563eb;
}

/* ===== LISTA MODULOS ===== */
.lista-aulas {
  list-style: none;
  padding: 0;
  margin: 0;
}

.lista-aulas li {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px;
  border-radius: 14px;
  margin-bottom: 10px;
  background: #f8fafc;
  border: 1px solid transparent;
  cursor: pointer;
  transition: all .2s ease;
}

.lista-aulas li:hover {
  background: #eff6ff;
  border-color: #bfdbfe;
  transform: translateX(4px);
  box-shadow: 0 6px 18px rgba(37, 99, 235, 0.08);
}

.lista-aulas li.active {
  background: linear-gradient(135deg, #dbeafe, #eff6ff);
  border-color: #93c5fd;
}

.lista-aulas i {
  font-size: 16px;
  color: #2563eb;
  min-width: 18px;
}

.lista-aulas span {
  font-size: 15px;
  font-weight: 500;
  color: #334155;
  line-height: 1.5;
}

/* ===== SUBLISTA ===== */
#AulasMod {
  list-style: none;
  padding: 0;
  margin-top: 14px;
}

#AulasMod li {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 14px !important;
  border-radius: 12px;
  margin-bottom: 8px;
  background: #ffffff;
  border: 1px solid #e5e7eb;
  cursor: pointer;
  transition: .2s;
  font-size: 14px;
}

#AulasMod li:hover {
  background: #f0f7ff;
  border-color: #93c5fd;
  transform: translateX(3px);
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
}

/* RESPONSIVO */
@media (max-width: 992px) {
  .layout {
    grid-template-columns: 1fr;
    height: auto;
  }

  .sidebar {
    height: auto;
  }
}

@media (max-width: 768px) {
  .sidebar {
    padding: 14px;
    border-radius: 16px;
  }

  .sidebar h4 {
    font-size: 16px;
  }

  .lista-aulas li {
    padding: 12px;
  }

  .lista-aulas span {
    font-size: 14px;
  }
}
Você está usando o nosso

  /* ===== PLAYER ===== */
  .player {
    display: flex;
    flex-direction: column;
    gap: 16px;
    min-width: 0;
  }

  /* ===== VIDEO ===== */
  .video-container {
    width: 100%;
    height: 72vh;
    background: #0b132b;
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    position: relative;
  }

  .video-container video {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background: #000;
  }

  /* ===== PLACEHOLDER ===== */
  .video-placeholder {
    text-align: center;
    color: #ffffff;
    padding: 20px;
  }

  .video-placeholder i {
    font-size: 82px;
    margin-bottom: 14px;
    color: #60a5fa;
  }

  .video-placeholder p {
    font-size: 17px;
    font-weight: 500;
    color: #e2e8f0;
  }

  /* ===== DESCRIÇÃO ===== */
  .aula-descricao {
    background: #ffffff;
    border-radius: 18px;
    padding: 18px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
  }

  .aula-descricao h4 {
    margin-bottom: 12px;
    font-weight: 700;
    color: #0f172a;
    font-size: 20px;
  }

  .aula-descricao h5 {
    margin: 0;
    font-size: 15px;
    font-weight: 500;
    color: #475569;
    line-height: 1.7;
  }

  /* ===== PROVA ===== */
  #prova {
    padding: 24px;
    overflow-y: auto;
  }

  #ListQuestion {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  /* ===== CARDS ===== */
  .card {
    border: none !important;
    border-radius: 18px !important;
    overflow: hidden;
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08) !important;
  }

  .card-header {
    background: linear-gradient(135deg, #2563eb, #3b82f6) !important;
    padding: 16px 20px !important;
    font-size: 16px;
  }

  .card-body {
    padding: 22px !important;
  }

  .card-body label {
    transition: .2s;
  }

  .card-body label:hover {
    background: #eff6ff;
    border-color: #93c5fd !important;
  }

  /* ===== BOTÃO ===== */
  .btn-primary {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    border: none;
    border-radius: 14px !important;
    padding: 12px 22px;
    font-weight: 600;
    box-shadow: 0 8px 18px rgba(37, 99, 235, 0.18);
  }

  .btn-primary:hover {
    transform: translateY(-1px);
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
  }

  /* ===== RESPONSIVO ===== */
  @media (max-width: 992px) {
    body {
      overflow: auto;
    }

    .layout {
      grid-template-columns: 1fr;
      height: auto;
    }

    .sidebar {
      height: auto;
    }

    .video-container {
      height: 48vh;
    }

    #prova {
      padding: 18px;
    }
  }

  @media (max-width: 768px) {
    .layout {
      padding: 12px;
      gap: 14px;
    }

    .sidebar {
      padding: 14px;
      border-radius: 16px;
    }

    .video-container {
      height: 36vh;
      border-radius: 16px;
    }

    .aula-descricao {
      padding: 16px;
      border-radius: 16px;
    }

    .video-placeholder i {
      font-size: 58px;
    }

    .video-placeholder p {
      font-size: 14px;
    }

    .sidebar h4 {
      font-size: 14px;
    }

    .lista-aulas li {
      padding: 9px;
    }
  }
</style>
</head>

<body>
  <nav class="navbar navbar-expand-lg shadow-sm" style="background-color:#4682B4;">
    <div class="container">

      <a class="navbar-brand fw-bold text-white" href="../newMenu.html" style="font-size:22px;">
        <i class="bi bi-mortarboard-fill me-1"></i> PTE
      </a>

      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <div class="navbar-nav align-items-lg-center gap-lg-3">

          <a class="nav-link text-white fw-semibold" href="../treinamentos.html">Treinamentos</a>

          <a class="nav-link text-white fw-semibold" href="../ranking.html">Ranking</a>
          <a class="nav-link text-white fw-semibold" href="../noticias.html">Notícias</a>
          <a class="nav-link text-white fw-semibold" href="../certificados.html">Certificados</a>

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
          <?php foreach ($modulos as $index => $modulo):

            $bloqueado = $index > $qtdProvas;
            ?>

            <li onclick="<?= $bloqueado ? '' : 'abrirModulo(' . $modulo['id'] . ')' ?>">

              <?php if ($bloqueado): ?>
                <i class="fa-solid fa-lock text-secondary"></i>
              <?php else: ?>
                <i class="fa-solid fa-folder"></i>
              <?php endif; ?>

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
          <i class="bi bi-play-fill"></i>
          <p class="animacaoGato">Cade a aula..? o ​🐈​😻​​ Comeu</p>
        </div>
      </div>

      <div class="aula-descricao">
        <h4><strong>Descrição da aula</strong></h4>
        <h5 id="descAula">

        </h5>
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


<?php if ($cursoConcluido): ?>

  <script>
    Swal.fire({
      title: "Voce concluiu este curso é possivel consultar seu Cerficado no menu anterior.",
      width: 600,
      padding: "3em",
      color: "#ffffff",
      background: "#6a6ea7 url(../public/confetti-35.gif)",
      backdrop: `
    rgba(0,0,123,0.4)
    url("../public/rocket.gif")
    left top
    no-repeat
  `
    });
  </script>

<?php endif; ?>
<script>
  async function abrirModulo(id) {
    let ul = document.getElementById("AulasMod");
    ul.style.display = "block";
    ul.innerHTML = "<li>Carregando aulas...</li>";

    let dados = new FormData();
    dados.append("idModulo", id);

    try {
      const resposta = await fetch("/PTE/routes/api.php?acao=listarAulas", {
        method: "POST",
        body: dados
      });

      const resp = await resposta.json();

      ul.innerHTML = "";

      if (!resp.aulas || resp.aulas.length === 0) {
        ul.innerHTML = "<li>Nenhuma aula encontrada</li>";
        return;
      }


      const aulasValidas = resp.aulas.filter(a => a.nome_aula && a.nome_aula.trim() !== "");

      if (aulasValidas.length === 0) {
        ul.innerHTML = "<li>Nenhuma aula válida encontrada</li>";
        return;
      }

      aulasValidas.forEach(function (aula) {
        let func;
        let icon;

        if (aula.tipo == 1) {
          func = "abrirAula";
        } else if (aula.tipo == 2) {
          func = "abrirProva";
        } else {
          func = "abrirAula";
        }

        if (aula.tipo == 2) {
          icon = "<i class='fa-solid fa-book-tanakh' style='color: rgb(99, 230, 190);'></i> ";
        } else {
          icon = "<i class='fa-solid fa-play-circle text-danger'></i> ";
        }

        ul.innerHTML +=
          "<li style='padding-left:25px; cursor:pointer;' onclick=\"" + func + "('" + aula.id + "')\">" +
          icon +
          "<span>" + aula.nome_aula + "</span>" +
          "</li>";
      });

    } catch (error) {
      ul.innerHTML = "<li>Erro ao carregar aulas</li>";
      console.error(error);
    }
  }


  async function abrirAula(id) {

    document.getElementById("prova").style.display = "none";
    document.getElementById("video").style.display = "block";

    let formdata = new FormData();
    formdata.append("idAula", id);

    const res = await fetch("../routes/api.php?acao=abrirAula", {
      method: "POST",
      body: formdata,
      credentials: "include"
    });

    const dados = await res.json();

    if (dados.aulas >= 1) {
      Swal.fire({
        icon: 'success',
        title: 'Você ja assistiu uma Vez esta aula',
        html: '<p>Deseja assistir novamente?</p>',
        showDenyButton: true,
        confirmButtonText: 'Sim',
        denyButtonText: `Nao`,
      }).then((result) => {
        if (result.isConfirmed) {
          if (dados && dados.dados && dados.dados.desc_midia) {
            document.getElementById("descAula").innerText = dados.dados.desc_midia;
          }

          if (dados.success && dados.dados && dados.dados.video) {

            document.querySelector(".video-container").innerHTML = `
      <video id="videoAula" controls autoplay style="width:100%; height:100%;">
        <source src="${dados.dados.video}" type="video/mp4">
      </video>
    `;

            controlarProgressoVideo(id);


          }
        }
      })

      return;
    } else {
      if (dados && dados.dados && dados.dados.desc_midia) {
        document.getElementById("descAula").innerText = dados.dados.desc_midia;
      }

      if (dados.success && dados.dados && dados.dados.video) {

        document.querySelector(".video-container").innerHTML = `
      <video id="videoAula" controls autoplay style="width:100%; height:100%;">
        <source src="${dados.dados.video}" type="video/mp4">
      </video>
    `;

        controlarProgressoVideo(id);


      }

    }

  };

  async function controlarProgressoVideo(idAula) {

    console.log("TEste");

    const video = document.getElementById("videoAula");

    let salvo = false;

    video.addEventListener("timeupdate", () => {

      if (!video.duration) return;

      let progresso = (video.currentTime / video.duration) * 100;

      if (progresso >= 90 && !salvo) {
        salvarVisualizacao(idAula);
        salvo = true;
      }

    });

  }

  async function salvarVisualizacao(idAula) {

    const dados = new FormData();
    dados.append("idAula", idAula);
    dados.append("idUser", localStorage.getItem("idUser"));

    await fetch("salvarVisualizacao.php", {
      method: "POST",
      body: dados,
      credentials: "include"
    });

  }






  async function abrirProva(id) {

    const idUser = localStorage.getItem("idUser");

    const dados = new FormData();
    dados.append("idprova", id);
    dados.append("iduser", idUser);


    console.log(idUser);
    Swal.fire({
      icon: 'question',
      title: 'Deseja iniciar a prova?',
      html: 'Voce tem certeza que deseja iniciar a prova? <strong> A prova só pode ser enviado uma unica vez, Faça com atenção!</strong>',
      showDenyButton: true,
      confirmButtonText: 'Sim',
      denyButtonText: `Nao`,
    }).then(async (result) => {

      if (result.isConfirmed) {


        const res = await fetch("iniProva.php", {
          method: "POST",
          body: dados,
          credentials: "include"
        });

        const resposta = await res.json();

        if (resposta.Feita) {
          Swal.fire({
            icon: 'error',
            title: 'Prova Feita',
            text: 'Voce ja realizou esta avaliação, por favor, aguarde o resultado!',
          })
        }

        if (resposta.sucesso) {

          iniciarProva(id);
        }

      } else {
        return;
      }

    });

  }

  async function iniciarProva(id) {



    document.getElementById("prova").style.display = "block";

    document.getElementById("video").style.display = "none";
    localStorage.setItem("idProva", id);


    console.log(id);

    let form = new FormData();
    form.append("idProva", id);

    const res = await fetch("../routes/api.php?acao=buscarQuestoes", {
      method: "POST",
      body: form,
      credentials: "include"
    });

    const resposta = await res.json();

    const lista = document.getElementById("ListQuestion");

    lista.innerHTML = "";

    let htmlTotal = "";

    resposta.Questoes.forEach(function (questao, index) {

      let html = `
  <li class="mb-4 m-5">
    <div class="card shadow-sm" style="max-width:1000px;border-radius:14px;">

      <div class="card-header text-white fw-semibold"
           style="background:#006edc;border-radius:14px 14px 0 0;">
        Questão ${index + 1}
      </div>

      <div class="card-body">

        <p class="mb-3">${questao.pergunta}</p>
  `;

      questao.alternativas.forEach(function (alt, num) {

        html += `
      <label class="d-block border rounded p-2 mb-2" style="cursor:pointer;">
        
        <input
          type="radio"
          name="q_${questao.id}"
          value="${alt.id_alternativa}"
          id="alt_${questao.id}_${alt.id}"
          style="margin-right:6px;"
        >

        Alternativa ${num + 1} : ${alt.texto}

      </label>
    `;

      });

      html += `
      </div>
      
    </div>
  </li>
  `;

      htmlTotal += html;

    });

    htmlTotal += `<button class="btn btn-primary m-5" style="border-radius:14px;width:1000px;" onclick="enviarProva(${id})">Enviar Prova</button>`;


    lista.innerHTML = htmlTotal;


  }


  async function enviarProva(id) {


    let respostas = [];

    document.querySelectorAll('input[type="radio"]:checked').forEach(function (radio) {

      let questao_id = radio.name.replace("q_", "");

      respostas.push({
        questao_id: questao_id,
        alternativa_id: radio.value
      });

    });

    let form = new FormData();
    form.append("idProva", id);
    form.append("respostas", JSON.stringify(respostas));
    form.append("idProva", localStorage.getItem("idProva"));

    console.log(respostas);

    const res = await fetch("corrigirProva.php", {
      method: "POST",
      body: form,
      credentials: "include"
    });

    const dados = await res.json();

    if (dados.reprova) {
      Swal.fire({
        icon: "error",
        title: "Prova enviada",
        text: " NOTA NÂO SUFICIENTE, ACERTOS" + " " + dados.acertos + " questões",

      })
    }

    if (dados.sucesso) {
      Swal.fire({
        icon: "success",
        title: "Prova enviada",
        text: "Você acertou " + dados.acertos + " questões"
      });

    }


  }


</script>
<script src="https://cdn.jsdelivr.net/npm/animejs/lib/anime.min.js"></script>

<script>
  anime({
    targets: '.animacaoGato',
    translateY: [20, 0],
    opacity: [0, 1],
    scale: [0.95, 1],
    duration: 900,
    easing: 'easeOutExpo',
    loop: true
  });

  async function oflog() {
    Swal.fire({
        icon: 'warning',
        title: 'Deseja realmente sair?',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Nao',
        backdrop: true,
        scrollbarPadding: false
    }).then(async (result) => {
        if (result.isConfirmed) {
            await fetch("control/logout.php", {
                method: "POST",
                credentials: "include"
            });
            localStorage.removeItem("token");
            localStorage.removeItem("idUser");
            localStorage.removeItem("tipoUsuario");
            localStorage.clear();
            window.location.href = "../index.html";
        }
    })


}
</script>