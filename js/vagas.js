

dadosvagas();
async function dadosvagas() {

    const res = await fetch("routes/api.php?acao=buscarVagas", {
        method: "POST",
        credentials: "include"
    })
    const dados = await res.json();
    console.log(dados);

    const tabela = document.getElementById("TabelaVagas");

    if (dados.length > 0) {
        tabela.innerHTML = dados.map((p) => `
            <tr>
                <td>${p.id}</td>
                <td>${p.titulo}</td>
                <td>${p.conteudo}</td>
                <td onclick="candidaturas(${p.id})" class="cursor-pointer btn btn-primary ">${p.total_candidaturas}</td>    
                <td>${p.data_vaga}</td>
                <td>
                    <button class="btn btn-danger" onclick="excluirVaga(${p.id})"><i class="fa-solid fa-trash"></i> Excluir</button>
                </td>
            </tr>
        `).join("");
    }
}

async function excluirVaga(id) {


  Swal.fire({
    icon: "warning",
    title: "Deseja realmente excluir essa vaga?",
    html: `
        <div>
        <h3>Usuarios candidatados</h3>
            <table border="1" style="width:100%; text-align:left;">
                <thead>
                    <tr>
                        <th>ID Candidatura</th>
                        <th>Nome</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody id="TabelaCandidaturas"></tbody>
            </table>
        </div>
    `,
    showConfirmButton: true,
    showCancelButton: true,
    confirmButtonText: "Sim",
    cancelButtonText: "Nao",
    backdrop: true,
    scrollbarPadding: false,

    didOpen: async () => {

        const form = new FormData();
        form.append("id", id);

        const res = await fetch("routes/api.php?acao=buscarCandidaturas", {
            method: "POST",
            credentials: "include",
            body: form
        });

        const dados = await res.json();

        const tabela = document.getElementById("TabelaCandidaturas");

        if (dados.length > 0) {
            tabela.innerHTML = dados.map((p) => `
                <tr>
                    <td>${p.id}</td>
                    <td>${p.nome}</td>
                    <td>${p.email}</td>
                </tr>
            `).join("");
        } else {
            tabela.innerHTML = `
                <tr>
                    <td colspan="3">Nenhuma candidatura encontrada</td>
                </tr>
            `;
        }
    }
}).then(async (result) => {
    if (result.isConfirmed) {
        const form = new FormData();
        form.append("idVaga", id);

        const res = await fetch("routes/api.php?acao=excluirVaga", {
            method: "POST",
            credentials: "include",
            body: form
        });
        
        const dados = await res.json();
        
        if(dados.success){
            Swal.fire({
                icon: "success",
                title: "Vaga excluida com sucesso",
                showConfirmButton: false,
                timer: 1500,
            });
            dadosvagas();
        }
    }
});

}