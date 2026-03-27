buscaProvas();

async function buscaProvas() {
    try {
        const dados = await fetch("dashbord/avaliacoes.php", {
            method: "GET",
            credentials: "include",
        });

        const res = await dados.json();

        if (res.success) {
            const tabela = document.getElementById('TabelaProvas');
            tabela.innerHTML = res.avaliacoes.map(p => `
        <tr>
            <td>${p.id_prova}</td>
            <td>${p.nome_aula}</td>
            <td>${p.nome}</td>
            <td>${p.acertos}</td>
            <td>${p.data_inicio}</td>
            <td>
                ${p.aprovado == 1
                    ? '<i class="fa-solid fa-check" style="color: #008fff;"></i>'
                    : '<i class="fa-solid fa-x" style="color: #ff4d4d;"></i>'} 
            </td> 
            <td>${p.porcentagem }%</td>
            <td>${p.qtd_questoes ?? ""}</td> >
            <td>${p.nota !== null 
                ? `<div class="d-flex justify-content-center align-items-center p-2"><span class="fw-bold pe-2"> ${p.nota}</span><i class="fa-solid fa-check" style="color: #00ff04;"></i> </div><div class="d-flex justify-content-center align-items-center"><button class=" btn btn-sm btn-success" onclick="editNota(${p.id_prova})">Editar Nota</button></div>`
                : '<div class="d-flex justify-content-center align-items-center p-2"><i class="fa-solid fa-x" style="color: #ff4d4d;"></i></div><div class="d-flex justify-content-center align-items-center"><button class=" btn btn-sm btn-primary" onclick="addNota(${p.id_prova})">Aplicar Nota</button></div> ' }</td>
            <td>
             
            <button class="btn btn-sm btn-info text-white">Mais informações</button>
            </td>
        </tr>
        `).join('');


        } else {
            console.error("Erro no servidor:", res.error);
        }

    } catch (error) {
        console.error("Erro na requisição:", error);
    }
}


async function addNota(id) {
    Swal.fire({
        icon:  "question",
        title: "Inserir Nota A avaliação",
        html: "<strong>As Notas sao de 100 a 1000, exemplo : Nota:800</strong> <div><label><h5>Preencha com A nota</h5></label></div><div><input class='form-control rounded-pill shadow-sm p-2 px-3'  id='notaP' placeholder='Nota entre 100 e 1000' style='border-color:black; border: 5px'></input></div>",
        width: '1000px'
    })
    
}