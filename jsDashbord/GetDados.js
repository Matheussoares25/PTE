

window.onload = function (){
  document.getElementById("FotoUser").src = localStorage.getItem("fotoUser") ?? "semFoto.jpg";
}

document.addEventListener('click', () => {
  checkLogin();
});

getDados();

async function getDados() {


  const res = await fetch("dashbord/dados.php", {
    method: "GET",
    credentials: "include",
  });

  const dados = await res.json();

  console.log(dados);

  if (dados.Negado) {
    Swal.fire({
      icon: "warning",
      title: "ACESSO NEGADO",
      html: "REALIZE LOGIN",
    });
    setTimeout(() => {
      window.location.href = "index.html";
    }, 1200);
  }

  document.getElementById("qtdAlunos").innerHTML = dados.alunos;
  document.getElementById("qtdCursos").innerHTML = dados.cursos;
  document.getElementById("qtdUsuarios").innerHTML = dados.usuarios;
  document.getElementById("qtdProvas").innerHTML = dados.provas;
  document.getElementById("qtdNoticias").innerHTML = dados.qtdNoticias;
  document.getElementById("qtdVagas").innerHTML = dados.qtdVagas;
  document.getElementById("qtdCertificados").innerHTML = dados.qtdCertificados;


  const ctx = document.getElementById("graficoProvas");
  const ctx2 = document.getElementById("graficoAlunos");
  const ctx3 = document.getElementById("graficoAulas");

  new Chart(ctx2, {
    type: "line",
    data: {
      labels: ["Alunos"],
      datasets: [
        {
          label: "Alunos",
          data: [dados.usuarios],
          backgroundColor: ["#36A2EB"],
        },
      ],
    },
    options: {
      responsive: true,
    },
  });

  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: ["Acertos", "Erros"],
      datasets: [
        {
          data: [dados.acertagem, 100 - dados.acertagem],
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
    },
  });

  new Chart(ctx3, {
    type: "doughnut",
    data: {
      labels: ["Aulas Criadas", "Aulas Assistidas", "Aulas excluidas"],
      datasets: [
        {
          data: [ dados.qtdAulas,  - dados.qtdAulasAssistidas, - dados.qtdAulasExcluidas],
          borderWidth: 1,
          backgroundColor: [
            "rgba(255, 0, 0, 0.75)",
            "rgba(0, 255, 81, 0.7)",
            "rgba(4, 4, 64, 0.7)",
            
          ],
        },
      ],
    },
    options: {
      responsive: true,
    },
  });

  const tabelaRanking = document.getElementById("TebelaRankingAlunos");

  tabelaRanking.innerHTML = dados.ranking
    .map(
      (p) => `
            <tr>
                
                <td>${p.nome}</td>
                <td>${p.total_notas}</td>
            </tr>
        `
    )
    .join("");
}

async function openCriados() {
  Swal.fire({
    title: "Cursos Criados",
    width: "800px",
    html: `
        <div class="d-flex justify-content-center">
            <table class="table table-bordered text-center w-75">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Status</th>
                        <th>Criado</th>
                    </tr>
                </thead>
                <tbody id="Tabela">
                </tbody>
            </table>
        </div>
        `,

    didOpen: async () => {
      try {
        const dados = await fetch("dashbord/dados.php", {
          method: "GET",
          credentials: "include",
        });

        const res = await dados.json();

        const tabela = document.getElementById("Tabela");

        tabela.innerHTML = res.tCursos
          .map(
            (p) => `
                
                <tr>
                    <td>${p.id}</td>
                    <td>${p.nome}</td>
                    <td>${p.status == 1 ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-danger">Inativo</span>'}</td>
                    <td>${p.criado}</td>    
                </tr>`,
          )
          .join("");
      } catch {
        document.getElementById("Tabela").innerHTML =
          `<tr><td colspan="4">Erro ao carregar dados</td></tr>`;
      }
    },
  });
}

async function openAlunos() {
  Swal.fire({
    title: "Alunos em curso",
    width: "1000px",
    html: `
    <div class="table-responsive">
        <table class="table table-sm table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Matrícula</th>
                    <th>ID Usuário</th>
                    <th>Nome Usuario</th>
                    <th>Curso</th>
                    <th>status/Matricula</th>
                    <th>Início</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="TabelaMatriculas">
            
            </tbody>
        </table>
    </div>
    `,

    didOpen: async () => {
      try {
        const dados = await fetch("dashbord/dados.php", {
          method: "GET",
          credentials: "include",
        });

        const res = await dados.json();

        const tabela = document.getElementById("TabelaMatriculas");

        tabela.innerHTML = res.tAlunos
          .map(
            (p) => `
                <tr>
                    <th>${p.matricula}</th>
                    <th>${p.id_usuario}</th>
                    <th>${p.nome_usuario}</th>
                    <th>${p.nome_curso}</th>
                    <th>${p.status_curso == 1 ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-danger">Inativo</span>'}</td>
                    <th>${p.data_curso.split(" ")[0].split("-").reverse().join("/")}</th>
                    <th><button class="btn btn-danger" onclick="excluirpermanente(${p.matricula})" ><i class="fa-solid fa-trash fa-flip-horizontal fa-xs" style="color: rgb(0, 0, 0);"></i> Exluir</button>
                    ${p.status_curso != 1 ? '<button class="btn btn-success"><i class="fa-solid fa-arrow-rotate-left fa-sm" style="color: rgb(0, 0, 0);"></i> Reativar</button>' : ''}
                `,
          )
          .join("");
      } catch {
        tabela.innerHTML = "Erro ao carregar dados";
      }
    },
  });
}

async function excluirpermanente(matricula) {
  Swal.fire({
    icon: "error",
    title: "Verificação de Permissão",
    html: "Tem certeza que deseja que deseja executar essa tarefa?<br><strong>Esta ação é irreversível!</strong> <div> <label> confirme o login como usuario para excluir essa prova </label> <input id='senhaLogin' type='text' class='swal2-input' placeholder='Senha'></div>",
    showCancelButton: true,
    confirmButtonText: "Sim, excluir",
    cancelButtonText: "Cancelar",
  }).then(async (result) => {
    if (result.isConfirmed) {

      const senhaLogin = document.getElementById("senhaLogin").value.trim();

      const formData = new FormData();
      formData.append("senha", senhaLogin);

      try {
        const res = await fetch("routes/api.php?acao=checkinAdm", {
          method: "POST",
          body: formData,
          credentials: "include",
        });

        const dados = await res.json();

        if (dados.success == false) {
          Swal.fire({
            icon: "error",
            title: "Erro",
            text: "Preenca com a senha do seu usario",
          });
        }

        if (dados.SENHAERRADA) {
          Swal.fire({
            icon: "error",
            title: "Erro",
            text: "Senha Incorreta",
          });
        }
        if (dados.LIBERADO) {

          const formData = new FormData();
          formData.append("matricula", matricula);

          const dados = await fetch("routes/api.php?acao=dashDeleteMatricula", {
            method: "POST",
            body: formData,
            credentials: "include",
          });
          const res = await dados.json();

          if (res.VAZIO) {
            Swal.fire({
              icon: "error",
              title: "Erro",
              text: "Matricula nao encontrada",
            });
          }
          if (res.success) {
            Swal.fire({
              icon: "success",
              title: "Matricula excluida",
              text: "Matricula excluida com sucesso",
            });
            getDados();
          }
        }

      } catch {

      }
    };
  });
}


async function openProvas() {
  Swal.fire({
    title: "Provas Realizadas",
    width: "800px",
    html: `
    <div class="table-responsive">
        <table class="table table-sm table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Nome Prova</th>
                    <th>Realizada por</th>
                    <th>Nº de Questões</th>
                    <th>Acertos</th>
                    <th>% de Acerto</th>
                    <th>Realizada em:</th>
                </tr>
            </thead>
            <tbody id="TabelaProvas">
            
            </tbody>
        </table>
    </div>
    `,

    didOpen: async () => {
      try {
        const dados = await fetch("dashbord/dados.php", {
          method: "GET",
          credentials: "include",
        });

        const res = await dados.json();

        const tabela = document.getElementById("TabelaProvas");

        tabela.innerHTML = res.dProvas
          .map(
            (p) => `
            <tr>
                    <td >${p.nome_prova ?? ""}</td>
                    <td id="btMiniDash" onclick="provaUse(${p.id_user})" style="cursor: pointer"><i class="fa-solid fa-info" style="color: rgb(0, 143, 255);"></i> ${p.nome_usuario}</td>
                    <td>${p.qtd_questoes ?? "Sem Respostas"}</td>
                    <td>${p.acertos ?? "Sem Repostas"}</td>
                    <td>${p.porcentagem ?? ""}</td>
                    <td>${p.data_inicio ? p.data_inicio.split(" ")[0].split("-").reverse().join("/") : ""}<th>
                    
                `,
          )
          .join("");
      } catch { }
    },
  });
}
