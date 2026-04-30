window.onload = function () {
    document.getElementById("FotoUser").src = localStorage.getItem("fotoUser") ?? "semFoto.jpg";
}

document.addEventListener('click', () => {

    checkLogin();
});

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
            <td>${p.porcentagem}%</td>
            <td>${p.qtd_questoes ?? ""}</td> >
            <td>${p.ordem_prova}º Do curso</td>
            <td>${p.nota !== null
                    ? `<div class="d-flex justify-content-center align-items-center p-2"><span class="fw-bold pe-2"> ${p.nota}</span><i class="fa-solid fa-check" style="color: #00ff04;"></i> </div><div class="d-flex justify-content-center align-items-center"><button class=" btn btn-sm btn-success" onclick="Notas(${p.id} ,${p.nota}, ${p.id_user}, ${p.id_prova})">Editar Nota</button></div>`
                    : `<div class="d-flex justify-content-center align-items-center p-2"><i class="fa-solid fa-x" style="color: #ff4d4d;"></i></div><div class="d-flex justify-content-center align-items-center"><button class=" btn btn-sm btn-primary" onclick="Notas(${p.id},${'null'}, ${p.id_user}, ${p.id_prova})">Aplicar Nota</button></div>`}</td>
            <td>
             
            <button class="btn btn-sm btn-info text-white" onclick="infoProva(${p.id_curso}, ${p.id_user}, '${p.nome}', '${p.nome_aula}')">Certificado/resumo</button>
            <button class="btn btn-sm btn-danger" onclick="excluirProva(${p.id}, ${p.id_user}, ${p.id_prova}, ${p.nota}, '${p.nome_aula}', '${p.nome}',${p.acertos},'${p.data_inicio}',${p.porcentagem},${p.qtd_questoes})">Excluir</button>

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

async function excluirProva(id, idUser, idProvabanco, nota = null, nomeAula, nomeAluno, acertos, data, porcentagem, qtd_questoes) {

    Swal.fire({
        icon: "question",
        width: 1200,
        title: "Excluir Prova",
        html: `<h5>Ao excluir um item do historico de provas, o usuario pode realizar novamente esta prova.</h5>
                <li>
                    <b>Nome da prova:</b> ${nomeAula}
                </li>
                <li>
                    <b>Realizada por:</b> ${nomeAluno}
                </li>
                <li>
                    <b>Acertos:</b> ${acertos}
                </li>
                <li>
                    <b>Data:</b> ${data}
                </li>
                <li>
                    <b>% acertos:</b> ${porcentagem}
                </li>
                <li>
                    <b>Qtd de questoes:</b> ${qtd_questoes}
                </li>
                <li>
                    <b>Nota:</b> ${nota}
                </li>
        
        
   `,
        text: "Tem certeza que deseja excluir essa prova?",
        showCancelButton: true,
        confirmButtonText: "Sim, excluir",
        cancelButtonText: "Cancelar",

    }).then(async (result) => {
        if (result.isConfirmed) {

            const res = await checkinAdm();

            if (!res) return false;

            const formData = new FormData();
            formData.append("idDohistoricoProva", id),
                formData.append("id_user", idUser)
            formData.append("idDaProvanoBanco", idProvabanco);

            try {
                const res = await fetch("dashbord/avaliacoes.php?action=excluir", {
                    method: "POST",
                    body: formData,
                    credentials: "include",
                });

                const result = await res.json();

                if (result.excluido) {
                    swal.fire({
                        icon: "success",
                        title: "Prova excluida com sucesso",
                        timer: 2000,
                        showConfirmButton: false
                    })
                    await buscaProvas();
                } else {
                    console.error("Erro no servidor:", result.error);
                }
            } catch (error) {
                console.error("Erro na requisição:", error);
            }

        }
    });
}

async function Notas(id, nota = null, idUser, idDaProvanoBanco) {


    Swal.fire({
        icon: "question",
        title: "Inserir Nota A avaliação",
        html: "<strong>As Notas sao de 100 a 1000, exemplo : Nota:800</strong> <div><label><h5>Preencha com A nota</h5></label></div><div><input class='form-control rounded-pill shadow-sm p-2 px-3'  id='notaP' placeholder='Nota entre 100 e 1000' style='border-color:black; border: 5px'></input></div>",
        width: '1000px',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: "Enviar",
        cancelButtonText: "Cancelar",

        didOpen: () => {
            if (nota !== null) {
                document.getElementById("notaP").value = nota;
            }
        },

        preConfirm: async () => {
            const nota = document.getElementById("notaP").value;

            if (nota > 1000 || nota < 100) {
                alert("A nota deve ser entre 100 e 1000");
                return;
            }

            const formData = new FormData();
            formData.append("idDohistoricoProva", id),
                formData.append("nota", nota)
            formData.append("id_user", idUser)
            formData.append("idDaProvanoBanco", idDaProvanoBanco)

            const dados = await fetch("dashbord/avaliacoes.php?action=avaliar", {
                method: 'POST',
                credentials: 'include',
                body: formData
            })

            const res = await dados.json();

            if (res.sucesso) {
                Swal.fire({
                    icon: "success",
                    title: "Nota Cadastrada",
                    timer: 2000,
                    showConfirmButton: false
                })

                buscaProvas();
            }

            if (res.update) {
                Swal.fire({
                    icon: "success",
                    title: "Nota Atualizada",
                    timer: 2000,
                    showConfirmButton: false
                })
                buscaProvas();
            }
        }
    })


}

async function infoProva(id_curso, user, nome, nome_aula) {
    const iduser = user;
    const idCurso = id_curso;


    const formData = new FormData();
    formData.append("iduser", iduser);
    formData.append("idCurso", idCurso);

    const dados = await fetch("control/provasFeitas.php", {
        method: "POST",
        body: formData,
        credentials: "include"
    });

    const res = await dados.json();

    Swal.fire({
        icon: "info",
        title: "Informações e Emissão de certificado",
        html: `
<div>
    <table border="1" style="width: 100%; text-align: center; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody id="TabelaProvasSwal">
        <tr>
                <td>Nome Do Curso</td>
                <td id="curso">${res.nome_curso}</td>
            </tr>
            <tr>
                <td>Provas feitas</td>
                <td id="provas_feitas">0</td>
            </tr>
            <tr>
            <td>Provas totais</td>
            <td id="provas_totais">0</td>
            </tr>
            <tr>
                <td>Feito Por</td>
                <td id="feito_por">${nome}</td>
            </tr>
            <tr>
                <td>Média</td>
                <td id="media">0%</td>
            </tr>
        </tbody>
    </table>
</div>
`,

        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: "Gerar Certificado",
        cancelButtonText: "Cancelar",


        didOpen: () => {
            const feitas = res.total_provas_feitas;
            const total = res.total_provas_curso;
            const media = res.media_porcentagem;
            const lista = res.porcentagens;

            document.getElementById("provas_feitas").textContent = feitas;
            document.getElementById("provas_totais").textContent = total;
            document.getElementById("media").textContent = media + "%";

            const TabelaProvas = document.getElementById("TabelaProvasSwal");

        
            TabelaProvas.innerHTML += lista.map((p, index) => `
        <tr>
            <td>${index + 1}ª Prova</td>
            <td style="font-weight:bold;">${p}%</td>
        </tr>
    `).join('');


            if (feitas === total && total > 0) {
                document.getElementById("provas_feitas").style.color = "green";
                document.getElementById("provas_totais").style.color = "green";
            }
            if(feitas < total){
                document.getElementById("provas_feitas").style.color = "red";
                document.getElementById("provas_totais").style.color = "red";
            }
        }
    }).then(async(result) => {
        if (result.isConfirmed) {
            
            const dados = new FormData();
            dados.append("idUser", user);
            dados.append("nomeAula", nome_aula);
            dados.append("nomeAluno", nome);

            const res = await fetch("routes/api.php?acao=gerarCertificado",{
                method: "POST",
                body: dados,
                credentials: "include"
            });
        }
    })



}