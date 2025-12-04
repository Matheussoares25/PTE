
treinamentosADM();
buscarTreinamentos();
treinamentosConcluidos();
async function buscarTreinamentos() {
    try {

        const idUser = localStorage.getItem("idUser");

        const data = new FormData();
        data.append("id", idUser);


        const res = await fetch("control/buscarTreinamentos.php", {
            method: "POST",
            body: data,
            credentials: "include",

        });

        const resposta = await res.json();

        if (resposta.erro) {
            Swal.fire({
                icon: "error",
                title: "Acesso negado",
                text: dados.erro
            });


            setTimeout(() => {
                window.location.href = "index.html";
            }, 1500);

            return;
        }

        const html = resposta.map(t => `
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                             <i class="bi bi-play-circle-fill text-secondary me-2" style="font-size: 1.7rem;"></i>
                            <strong>${t.nome}</strong>
                        </div>
                         <a href="treinamento.php?id=${t.id}" class="btn btn-sm btn-primary mt-2">
                                  Acessar
                        </a>
                    </div>
        `).join("");

        document.getElementById("listaTreinamentos").innerHTML = html;

    } catch (error) {
        console.error("Erro ao buscar treinamentos:", error);
        document.getElementById("listaTreinamentos").innerHTML =
            "<p>Não foi possível carregar os treinamentos.</p>";
    }


    const cargo = localStorage.getItem("tipoUsuario");

    if (cargo == 2) {
        document.getElementById("noAdm").style.display = "none";


    } else {
        document.getElementById("siAdm").style.display = "none";
    }

}

async function treinamentosConcluidos() {
    try {

        const idUser = localStorage.getItem("idUser");

        const data = new FormData();
        data.append("id", idUser);


        const res = await fetch("control/treinamentosConcluidos.php", {
            method: "POST",
            dataType: "json",
            body: data,
            credentials: "include"
        });
        const dados = await res.json();

        if (dados.erro) {
            Swal.fire({
                icon: "error",
                title: "Acesso negado",
                text: dados.erro
            });


            setTimeout(() => {
                window.location.href = "index.html";
            }, 1500);

            return;
        }

        document.getElementById("Concluidos").innerHTML = dados.map(item => `
            <tr>
                <td>${item.nome}</td>
                <td>${item.data ?? '-'}</td>
            </tr>
        `).join("");

    } catch (error) {
        console.error("Erro:", error);
        document.getElementById("Concluidos").innerHTML =
            `<tr><td colspan="2">Erro ao carregar.</td></tr>`;
    }
}

async function treinamentosADM() {
    try {
        const idUser = localStorage.getItem("idUser");

        const data = new FormData();
        data.append("id", idUser);

        const res = await fetch("control/treinamentoADM.php");

        const dados = await res.json();

        if (dados.erro) {
            Swal.fire({
                icon: "error",
                title: "Acesso negado",
                text: dados.erro
            });


            setTimeout(() => {
                window.location.href = "index.html";
            }, 1500);

            return;
        }

        const html = dados.map(t => `
            <div class="mb-3">
                        <div class="d-flex align-items-center">
                             <i class="bi bi-play-circle-fill text-secondary me-2" style="font-size: 1.7rem;"></i>
                            <strong>${t.nome}</strong>
                        </div>
                        <span>
    <button class="btn btn-primary" onclick="editTreinamento()">INFO</button>
</span>
                    </div>`).join("");

        document.getElementById("TreinamentosADM").innerHTML = html;


    } catch (error) {
        console.error("Erro ao buscar treinamentos:", error);
        document.getElementById("TreinamentosADM").innerHTML =
            "<p>Não foi possível carregar os treinamentos.</p>";
    }
}


async function editTreinamento() {
    
    Swal.fire({
        title: "Informações do Curso",
        width: "80%",
        html: `
            <div class="text-start">

                <p><strong>ID:</strong> </p>
                <p><strong>Nome:</strong> </p>
                <p><strong>Data de Criação:</strong> </p>

            </div>
        `,

        showConfirmButton: true,
        showDenyButton: true,

        confirmButtonText: "Editar Informações do Curso",
        denyButtonText: "Lista de Cadastrados"
    }).then(result => {

        if (result.isConfirmed) {
            editarCurso();
        }

        if (result.isDenied) {
            Swal.fire({
    title: "Cadastrados no Curso",
    width: "80%",
    html: `
        <div class="table-responsive text-start" style="max-height:400px; overflow-y:auto;">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Data de Criação</th>
                    </tr>
                </thead>
                <tbody>
                  
                </tbody>
            </table>
        </div>
    `,
    showConfirmButton: true,
    confirmButtonText: "Fechar"
});
        }
    });
}

