Noticias();

async function Noticias() {
  try {
    const res = await fetch("routes/api.php?acao=buscarNoticias", {
      method: "POST",
      credentials: "include",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({}),
    });

    const dados = await res.json();

    const html = dados
      .map(
        (n) => `
<div class="card card-modern mb-4">
    <div class="card-body">

        <h5 class="card-title">${n.titulo}</h5>
        <p class="card-text">${n.conteudo}</p>
        <p class="card-text">
            <small class="text-muted">${n.data_noticia}</small>
        </p>

        <div class="btn-group-modern">
            <button class="btn btn-card btn-edit btneditar" onclick="editarNoticia(${n.id}, '${n.titulo}', '${n.conteudo}')">
                <i class="bi bi-pencil-square me-1"></i> Editar
            </button>

            <button class="btn btn-card btn-del btneditar" onclick="exNoticia(${n.id})">
                <i class="bi bi-trash me-1"></i> Excluir
            </button>
             ${n.vaga == 1
            ? `
                <button class="btn btn-info btn-card" onclick="Candidatar(${n.id})">
                 <i class="fa-solid fa-arrow-trend-up" style="color: rgb(0, 0, 0);"></i> Candidatar-se
                </button>
`
            : ``
          }
            </div>

         </div>
        </div>
      `,
      )
      .join("");

    document.getElementById("ListaNoticias").innerHTML = html;

    if (localStorage.getItem("tipoUsuario") != 2) {
      document.querySelectorAll(".btneditar").forEach((btn) => {
        btn.style.display = "none";
      });
    }
  } catch (error) {
    console.log("Erro no fetch:", error);

    Swal.fire({
      icon: "error",
      title: "Erro",
      text: "Acesso negado",
    });

    setTimeout(() => {
      window.location.href = "index.html";
    }, 1000);
  }
}

const cargo = localStorage.getItem("tipoUsuario");

if (cargo != 2) {
  document.querySelectorAll(".btnadm").forEach((el) => {
    el.style.display = "none";
  });
}

async function addNoticia() {
  Swal.fire({
    html: `
        <div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Criar Nova Notícia</h4>
        </div>

        <div class="card-body">
            <form id="formNovaNoticia">

                <!-- Campo Título -->
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" id="titulo" class="form-control" placeholder="Digite o título da notícia" required>
                </div>

                <!-- Campo Conteúdo -->
                <div class="mb-3">
                    <label for="conteudo" class="form-label">Conteúdo:</label>
                    <textarea id="conteudo" class="form-control" rows="5" placeholder="Escreva o conteúdo da notícia..." required></textarea>
                </div>

                <!--Campo marcar como vaga-->
                <div class="form-check">
                <input class="form-check-input" type="checkbox" id="marcaVaga" style="cursor: pointer; accent-color: blue;">
                    <label class="form-check-label" type="checkbox" for="marcaVaga" style="cursor: pointer; color: blue;">
                    
                         Marcar como Vaga
                    </label>
                </div>

             

            </form>
        </div>
    </div>
</div>
        `,
    showCancelButton: true,
    confirmButtonText: "Adicionar",
    showLoaderOnConfirm: true,
    width: "50%",

    preConfirm: async () => {
      const titulo = document.getElementById("titulo").value;
      const conteudo = document.getElementById("conteudo").value;
      const checkbox = document.getElementById("marcaVaga");

      if (titulo === "" || titulo === null || titulo.length <= 3) {
        Swal.showValidationMessage(
          "O Titulo deve sem preenchido (Com mais de 3 caracteres)",
        );
        return false;
      }

      let check = checkbox.checked ? 1 : 2;

      const formData = new FormData();
      formData.append("titulo", titulo);
      formData.append("conteudo", conteudo);
      formData.append("vaga", check);

      try {
        const res = await fetch("routes/api.php?acao=salvarNoticia", {
          method: "POST",
          body: formData,
          credentials: "include",
        });

        const dados = await res.json();

        if (dados.erro) {
          Swal.showValidationMessage(dados.erro);
          return false;
        }

        return dados;
      } catch (error) {
        Swal.showValidationMessage("Erro na requisição");
        return false;
      }
    },
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        icon: "success",
        title: "Sucesso",
        text: "Notícia criada com sucesso!",
        timer: 3000,
      }).then(() => {
        window.location.reload();
      });
    }
  });
}

async function exNoticia(id) {
  const formdata = new FormData();
  formdata.append("id", id);

  Swal.fire({
    icon: "warning",
    title: "Deseja realmente excluir essa noticia?",
    showConfirmButton: true,
    showCancelButton: true,
    confirmButtonText: "Sim",
    cancelButtonText: "Nao",
    backdrop: true,
    scrollbarPadding: false,
  }).then(async (result) => {
    if (result.isConfirmed) {
      const res = await fetch("routes/api.php?acao=excluirNoticia", {
        method: "POST",
        credentials: "include",
        body: formdata,
      });
      const dados = await res.json();

      if (dados.erro) {
        Swal.fire({
          icon: "error",
          title: "Erro",
          text: "dados.erro",
        });
      } else {
        Swal.fire({
          icon: "success",
          title: "Sucesso",
          text: "Noticia excluida com sucesso!",
        }).then(() => {
          location.reload();
        });
      }
    }
  });
}

async function editarNoticia(id, titulo, conteudo) {



  Swal.fire({
    html: `<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Editar Noticia ${titulo}</h4>
        </div>

        <div class="card-body">
            <form id="formNovaNoticia">

                <!-- Campo Título -->
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" id="titulo" class="form-control" placeholder="Digite o título da notícia" required>
                </div>

                <!-- Campo Conteúdo -->
                <div class="mb-3">
                    <label for="conteudo" class="form-label">Conteúdo:</label>
                    <textarea id="conteudo" class="form-control" rows="5" placeholder="Escreva o conteúdo da notícia..." required></textarea>
                </div>

             

            </form>
        </div>
    </div>
</div>`,
    showCancelButton: true,
    confirmButtonText: "Editar",
    confirmButtonColor: "#3085d6",
  }).then(async (result) => {
    if (result.isConfirmed) {
      const titulo = document.getElementById("titulo").value;
      const conteudo = document.getElementById("conteudo").value;

      if (titulo === "" || titulo === null || titulo.length <= 3) {
        Swal.fire({
          icon: "error",
          title: "Erro",
          text: "O Titulo deve sem preenchido (Com mais de 3 caracteres)",
        });
        return false;
      }
      const formData = new FormData();
      formData.append("titulo", titulo);
      formData.append("conteudo", conteudo);
      formData.append("id", id);

      const res = await fetch("control/editarNoticia.php", {
        method: "POST",
        body: formData,
        credentials: "include",
      });

      const dados = await res.json();

      if (dados.erro) {
        Swal.fire({
          icon: "error",
          title: "Erro",
          text: dados.erro,
        });
      } else {
        Swal.fire({
          icon: "success",
          title: "Sucesso",
          text: "Noticia editada com sucesso!",
          timer: 3000,
        }).then(() => {
          location.reload();
        });
      }
    }
  });
}

async function Candidatar(id) {
  const vagas = await fetch("routes/api.php?acao=buscarVagas", {
    method: "POST",
    credentials: "include",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({}),
  });

  const vagasJson = await vagas.json();
  console.log(vagasJson);

  const options = vagasJson
    .map((v) => `<option value="${v.id}">${v.titulo}</option>`)
    .join("");

  const result = await Swal.fire({
    icon: "question",
    title: "Deseja declarar interesse Em Alguma de nossas vagas?",
    html: ` Ao declarar interesse em uma vaga, vocé concorda com as regras e condutas da empresa. Fique tranquilo, você pode recusar caso deseje. <strong>Vagas:</strong><br>
<select id="vagaSelect" class="swal2-select" style="width:50%; font-size:12px;">
  <option value="">Vagas</option>
  ${options}
</select>
`,
    confirmButtonText: "Sim, candidatar",
    cancelButtonText: "Cancelar",
    showCancelButton: true,
    padding: "3em",
    color: "#000000",
  });

  if (result.isConfirmed) {
    const idUser = localStorage.getItem("idUser");
    const idVaga = document.getElementById("vagaSelect").value;

    const formdata = new FormData();
    formdata.append("idvaga", idVaga);
    formdata.append("iduser", idUser);

    const res = await fetch("routes/api.php?acao=candidatar", {
      method: "POST",
      body: formdata,
      credentials: "include",
    });

    const status = await res.json();

    if (status.success) {
      Swal.fire({
        icon: "success",
        title: "Candidatura enviada!",
        timer: 2000,
        showConfirmButton: false,
      });
    }

    if (status.Existe) {
      Swal.fire({
        icon: "warning",
        title: "Candidatura já enviada!",
        timer: 2000,
        showConfirmButton: false,
      });
    }

  }
}
