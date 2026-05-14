    if (localStorage.getItem("tipoUsuario") != 2) {
      document.querySelectorAll(".btnsAdm").forEach((btn) => {
        btn.style.display = "none";
      });
    }

buscaProvas();


async function buscaProvas() {
    try {
        const dados = await fetch("routes/api.php?acao=buscaCertificados", {
            method: "GET",
            credentials: "include",
        });

        const res = await dados.json();

        if (res.success) {
            const tabela = document.getElementById('TabelaCertificados');
            tabela.innerHTML = res.certificados.map(p => `
        <tr>
            <td>${p.id_certificado}</td>
            <td>${p.emitido}</td>
            <td>${p.nome_curso}</td>
            <td>${p.nome_usuario}</td>
            <td>${p.token}</td>
            <td><button class="btn btn-danger btnsAdm" onclick="excluirUsuario(${p.id})" ><i class="fa-solid fa-trash fa-flip-horizontal fa-xs" style="color: rgb(0, 0, 0);"></i> </button>
            <button class="btn btn-warning btnsAdm" onclick="perfil(${p.id})" ><i class="fa-solid fa-pen-to-square fa-xs" style="color: rgb(0, 0, 0);"></i> </button>
              <a href="control/certificado.php?idCertificado=${p.id_certificado}" class="btn btn-sm btn-primary mt-2"><i class="bi bi-eye-fill"></i></td>
           
        </tr>
        `).join('');


        } else {
            console.error("Erro no servidor:", res.error);
        }


    } catch (error) {
        console.error("Erro na requisição:", error);
    }
}

async function visualizar(idUsern , idcurso){

    const idUser = idUsern
    const idCurso = idcurso

    const dados = new FormData();
    dados.append("idUser", idUser);
    dados.append("idCurso", idCurso);


    const res = await fetch("control/certificado.php?idUser="+idUser+"&nomeCurso="+idCurso)

    const dadosRes = await res.json();


}