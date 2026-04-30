

window.onload = function () {
  document.getElementById("FotoUser").src = localStorage.getItem("fotoUser") ?? "semFoto.jpg";
}

document.addEventListener('click', () => {
  checkLogin();
});

getDados();

setInterval(() => {
  getDados();
}, 5000);

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
  document.getElementById("qtdProblemas").innerHTML = dados.qtdReports;


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
          data: [dados.qtdAulas, - dados.qtdAulasAssistidas, - dados.qtdAulasExcluidas],
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
                ${p.status_curso != 1
                ? `<button class="btn btn-success" onclick="alterarMatricula(${p.matricula})">
            <i class="fa-solid fa-arrow-rotate-left fa-sm" style="color: rgb(0, 0, 0);"></i> 
            Reativar
       </button>`
                : `<button class="btn btn-warning" onclick="alterarMatricula(${p.matricula})">
            <i class="fa-solid fa-arrow-rotate-left fa-sm" style="color: rgb(0, 0, 0);"></i> 
            Desativar Matrícula
       </button>`
              }                `,
          )
          .join("");
      } catch {
        tabela.innerHTML = "Erro ao carregar dados";
      }
    },
  });
}

async function alterarMatricula(matricula){

  const verifica = await checkinAdm();

  if (!verifica) return false;

  const dados = new FormData();

  dados.append("matricula", matricula);

  const res = await fetch("routes/api.php?acao=alterarMatricula", {
    method: "POST",
    body: dados,
    credentials: "include",
  });

  const result = await res.json();

  if (result.success) {
    Swal.fire({
      icon: "success",
      title: `Matricula ${matricula} alterada`,
      text: "Matricula alterada com sucesso",
      ShowconfirmButton: false,
    });
    setTimeout(() => {
      openAlunos();
    },1500)
    
  }
}

async function excluirpermanente(matricula) {

  const verificar = await checkinAdm();

  if (!verificar) return false;


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

async function openReports() {
  Swal.fire({
    title: "Problemas Relatados",
    width: "1200px",
    html: `
    <div class="table-responsive">
        <table class="table table-sm table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th style="width: 20%">Usuario</th>
                    <th>Report</th>
                    <th style="width: 20%">Acoes</th>
                </tr>
            </thead>
            <tbody id="TabelaReports">
            
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

        const tabela = document.getElementById("TabelaReports");

        tabela.innerHTML = res.dReports
          .map(
            (p) => `
            <tr>
                    <td >${p.nome ?? ""}</td>
                    <td>${p.reclamacao ?? ""}</td>
                    <td><button class="btn btn-danger" onclick="excluirReport(${p.id})" ><i class="fa-solid fa-trash fa-flip-horizontal fa-xs" style="color: rgb(0, 0, 0);"></i> Exluir</button></td>   
                `,
          )
          .join("");
      } catch { }
    },
  });
}

async function excluirReport(id) {

  const verificar = await checkinAdm();

  if (!verificar) return false;


  try {
    const dadosrequest = new FormData();
    dadosrequest.append("id", id);

    const dados = await fetch("routes/api.php?acao=deletarReport", {
      method: "POST",
      body: dadosrequest,
      credentials: "include",
    });

    const res = await dados.json();
    if (res.success) {
      Swal.fire({
        icon: "success",
        title: "Report excluido",
        text: "Report excluido com sucesso",
      });
      getDados();


    }
  } catch { }
}



async function openUsuarios() {
  Swal.fire({
    title: "Usuarios Cadastrados",
    width: "1200px",
    html: `
    <div class="table-responsive">
        <table class="table table-sm table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th >Email</th>
                    <th>Tipo de Usuario</th>
                    <th >Nome</th>
                    <th>Ativo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="TabelaReports">
            
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

        const tabela = document.getElementById("TabelaReports");

        tabela.innerHTML = res.dUsuarios
          .map(
            (p) => `
            <tr>
                    <td>${p.nome ?? ""}</td>
                    <td >${p.email ?? ""}</td>
<td>${p.tipo == 2 ? `Admin` : `Funcionario <button class="btn btn-warning ms-4" onclick="alterarTipo(${p.id})">Alterar</button>`}</td>
                    <td>${p.acess == 1 ? `<button onclick="alterarAcesso(${p.id})" class="btn btn-success">Ativo</button>` : `<button onclick="alterarAcesso(${p.id})" class="btn btn-danger">Bloqueado</button>`}</td>
                    <td><button class="btn btn-danger" onclick="excluirUsuario(${p.id})" ><i class="fa-solid fa-trash fa-flip-horizontal fa-xs" style="color: rgb(0, 0, 0);"></i> Exluir</button>
                    <button class="btn btn-warning" onclick="perfil(${p.id})" ><i class="fa-solid fa-pen-to-square fa-xs" style="color: rgb(0, 0, 0);"></i> Editar</button></td>
                      
                `,
          )
          .join("");
      } catch { }
    },
  });

}



async function alterarAcesso(id) {
  console.log(id);
  const liberado = await checkinAdm();

  if (!liberado) return;

  const dadosrequest = new FormData();
  dadosrequest.append("id", id);

  const dados = await fetch("routes/api.php?acao=alterarTipoDeAcesso", {
    method: "POST",
    body: dadosrequest,
    credentials: "include",
  });

  const res = await dados.json();

  if (res.success) {
    Swal.fire({
      icon: "success",
      title: "ACesso Alterado",
      text: "Alterado com sucesso",
    });
    openUsuarios();

  }
}

async function alterarTipo(id) {

  const liberado = await checkinAdm();

  if (!liberado) return;

  const dadosrequest = new FormData();
  dadosrequest.append("id", id);

  const dados = await fetch("routes/api.php?acao=alterarTipo", {
    method: "POST",
    body: dadosrequest,
    credentials: "include",
  });

  const res = await dados.json();

  if (res.success) {
    Swal.fire({
      icon: "success",
      title: "Tipo Alterado",
      text: "Tipo alterado com sucesso",
    });
    openUsuarios();
  }

}

async function excluirUsuario(id) {

  const liberado = await checkinAdm();

  if (!liberado) return;

  try {
    const dadosrequest = new FormData();
    dadosrequest.append("id", id);

    const dados = await fetch("routes/api.php?acao=deletarUsuario", {
      method: "POST",
      body: dadosrequest,
      credentials: "include",
    });

    const res = await dados.json();
    if (res.success) {
      Swal.fire({
        icon: "success",
        title: "Usuario excluido",
        text: "Usuario excluido com sucesso",
      });
      openUsuarios();
    }
    if (res.success == false) {
      Swal.fire({
        icon: "error",
        title: "Usuario Não pode ser excluido",
        text: res.erro,
      });
    }
  } catch { }
}

