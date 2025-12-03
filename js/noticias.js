
Noticias();

async function Noticias() {
    try {
        const res = await fetch("control/noticias.php", {
            method: "POST",
            credentials: "include",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({})
        });

        const dados = await res.json();

        const html = dados.map(n => `
        <div class="card mb-4">
            <div class="card-body">

                <h5 class="card-title">${n.titulo}</h5>
                <p class="card-text">${n.conteudo}</p>
                <p class="card-text"><small class="text-muted">${n.data_noticia}</small></p>

                <button class="btneditar btn btn-success" onclick="editarNoticia(${n.id})">
                    Editar Notícia
                </button>

                <button class="btneditar btn btn-danger" onclick="exNoticia(${n.id})">
                    Excluir Noticia
                </button>

            </div>
        </div>
    `).join("");

        document.getElementById("ListaNoticias").innerHTML = html;


        if (localStorage.getItem("tipoUsuario") != 2) {
            document.querySelectorAll(".btneditar").forEach(btn => {
                btn.style.display = "none";
            });
        }

    } catch (error) {
        console.log("Erro no fetch:", error);

        Swal.fire({
            icon: "error",
            title: "Erro",
            text: "Falha ao carregar as notícias."
        });
    }
}



const cargo = localStorage.getItem("tipoUsuario");

if (cargo != 2) {
    document.getElementById("btnadm").style.display = "none";

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

             

            </form>
        </div>
    </div>
</div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Adicionar',
        showLoaderOnConfirm: true,
        width: '50%',
    }).then(async (result) => {
        if (result.isConfirmed) {
            const titulo = document.getElementById("titulo").value;
            const conteudo = document.getElementById("conteudo").value;

            const formData = new FormData();
            formData.append("titulo", titulo);
            formData.append("conteudo", conteudo);

            const res = await fetch("control/addNoticia.php", {
                method: "POST",
                body: formData,
                credentials: "include"
            });

            const dados = await res.json();

            if (dados.erro) {
                Swal.fire({
                    icon: "error",
                    title: "Erro",
                    text: dados.erro
                });
            } else {
                Swal.fire({
                    icon: "success",
                    title: "Sucesso",
                    text: "Noticia criada com sucesso!",
                    timer: 3000
                }).then(() => {
                    window.location.reload();
                });


            }
        }
    })

}

async function exNoticia(id) {

    const formdata = new FormData();
    formdata.append("id", id);

    Swal.fire({
        icon: "warning",
        title: "Deseja realmente excluir essa noticia?",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Nao',
        backdrop: true,
        scrollbarPadding: false
    }).then(async (result) => {
        if (result.isConfirmed) {
            const res = await fetch("control/exNoticia.php", {
                method: "POST",
                credentials: "include",
                body: formdata
            });
            const dados = await res.json();

            if (dados.erro) {
                Swal.fire({
                    icon: "error",
                    title: "Erro",
                    text: "dados.erro"
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
    })
}

async function editarNoticia(id) {
    formdata = new FormData();
    formdata.append("id", id);

    const res = await fetch("control/editarNoticia.php", {
        method: "POST",
        credentials: "include",
        body: formdata
    });
    const dados = await res.json();
    console.log(dados);
      Swal.fire({
        html: `<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Editar Noticia ${dados.Noticia.titulo}</h4>
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
        confirmButtonText: 'Editar',
        confirmButtonColor: '#3085d6',
    }).then(async (result) => {
        if (result.isConfirmed) {
            const titulo = document.getElementById("titulo").value;
            const conteudo = document.getElementById("conteudo").value;

            const formData = new FormData();
            formData.append("titulo", titulo);
            formData.append("conteudo", conteudo);
            formData.append("id", id);

            const res = await fetch("control/editarNoticia.php", {
                method: "POST",
                body: formData,
                credentials: "include"
            });

            const dados = await res.json();

            if (dados.erro) {
                Swal.fire({
                    icon: "error",
                    title: "Erro",
                    text: dados.erro
                });
            } else {
                Swal.fire({
                    icon: "success",
                    title: "Sucesso",
                    text: "Noticia editada com sucesso!",
                    timer: 3000
                }).then(() => {
                    location.reload();
                });
            }
        }
    })
}