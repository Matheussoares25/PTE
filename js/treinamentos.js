


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


