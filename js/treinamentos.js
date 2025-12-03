const token = localStorage.getItem('token');

if (!token) {
    alert("Você precisa realizar login primeiro");
    window.location.href = "index.html";
} else {
    const formData = new FormData();
    formData.append('token', token);

    fetch('control/verificatoken.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (!data.valido) {
                localStorage.removeItem('token');
                alert("login expirado, faça login novamente");
                window.location.href = "index.html";
            }
        });
}
function oflog(){
    localStorage.clear();
    window.location.href = "index.html";
}

buscarTreinamentos();
treinamentosConcluidos();
async function buscarTreinamentos() {
    try {
        
        const res = await fetch("control/buscarTreinamentos.php",{
            method: "POST",
            data: {id: localStorage.getItem("idUser"),}
        });
    
        const resposta = await res.json();

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
        const res = await fetch("control/treinamentosConcluidos.php",{
            method: "POST",
            dataType: "json",
            data: {id: localStorage.getItem("idUser"),}
        });
        const dados = await res.json();

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


