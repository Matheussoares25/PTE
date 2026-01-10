

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
                         <a href="control/treinamento.php?id=${t.id_curso}" class="btn btn-sm btn-primary mt-2">
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
            <div class="mb-3 p-3 border rounded d-flex justify-content-between align-items-center shadow-sm" 
     style="background:#fdfdfd;">
    
    <div class="d-flex align-items-center">
        <i class="bi bi-book me-2 text-primary" style="font-size: 1.2rem;"></i>
        <strong style="font-size:1.05rem;">${t.nome}</strong>
    </div>

    <button class="btn btn-light btn-sm border" 
            style="padding:4px 10px;" 
            onclick="editTreinamento('${t.id}')">
        <i class="bi bi-info-circle"></i>
    </button>

</div>`).join("");

        document.getElementById("TreinamentosADM").innerHTML = html;


    } catch (error) {
        console.error("Erro ao buscar treinamentos:", error);
        document.getElementById("TreinamentosADM").innerHTML =
            "<p>Não foi possível carregar os treinamentos.</p>";
    }
}


async function editTreinamento(id) {

    const formdata = new FormData();
    formdata.append("id", id);

    const res = await fetch("control/buscaT.php", {
        method: "POST",
        credentials: "include",
        body: formdata
    });

    const dados = await res.json();


    Swal.fire({
        title: "Informações do Curso",
        width: "30%",
        html: `
        <div class="text-start">
            <p><strong>ID: </strong> ${dados.treinamentos[0].id}</p>
            <p><strong>Nome: </strong> ${dados.treinamentos[0].nome}</p>
            <p><strong>Status: </strong> ${dados.treinamentos[0].status}</p>
            <p><strong>Criado em: </strong> ${dados.treinamentos[0].criado}</p>
        </div>
    `,

        showConfirmButton: true,
        showDenyButton: true,

        confirmButtonText: "Editar Informações do Curso",
        denyButtonText: "Lista de Cadastrados"
    }).then(async result => {

        if (result.isConfirmed) {
            editarCurso();
        }

        if (result.isDenied) {

            data = new FormData();
            data.append("id", id)

            console.log(data);

            const resposta = await fetch("control/buscaT.php", {
                method: "POST",
                credentials: "include",
                body: data

            });
            const dados = await resposta.json();

            const nome = dados.treinamentos[0].nome;
            const idcurso = dados.treinamentos[0].id;

            Swal.fire({
                title: `
        <div style="display:flex; align-items:center; gap:10px; justify-content:center;">
            <i class="fa-solid fa-users" style="font-size:32px; color:#3085d6;"></i>
            <span style="font-weight:700;">Cadastrados no Curso: ${nome} </span>
           
        </div>
    `,
                width: "35%",
                confirmButtonText: "Cadastrar ao Curso",
                cancelButtonText: "Fechar",
                confirmButtonColor: "#3085d6",
                showCancelButton: true,

                html: `
        <div class="table-wrapper" 
             style="max-height:420px; overflow-y:auto; border-radius:12px; box-shadow:0 0 10px rgba(0,0,0,0.15);">
            
            <table style="
                width:100%;
                border-collapse:collapse;
                font-family: Arial, Helvetica, sans-serif;
                font-size:15px;
            ">
                <thead>
                    <tr style="background:#3085d6; color:white;">
                        <th style="padding:12px; text-align:left;"> ID / Matrícula</th>
                        <th style="padding:12px; text-align:left;"> Email</th>
                        <th style="padding:12px; text-align:left;"> Data Início</th>
                        <th style="padding:12px; text-align:center;"> Ações</th>
                    </tr>
                </thead>

                <tbody id="relacionados" style="background:white;"></tbody>
            </table>
        </div>
    `,
                didOpen: () => {
                    const tbody = document.getElementById("relacionados");

                    dados.relacionados.forEach(item => {
                        tbody.innerHTML += `
                       <tr>
    <td style="padding:8px;">
        <i class="fa-solid fa-id-badge" style="color:#555; margin-right:6px;"></i>
        ${item.matricula}
    </td>

    <td style="padding:8px;">
        <i class="fa-solid fa-envelope" style="color:#777; margin-right:6px;"></i>
        ${item.email}
    </td>

    <td style="padding:8px;">
        <i class="fa-solid fa-calendar-day" style="color:#777; margin-right:6px;"></i>
        ${item.data_curso}
    </td>

    <td style="padding:8px; text-align:center;">
        <button 
    style="background:#3085d6; color:white; border:none; padding:6px 10px; border-radius:4px; cursor:pointer;"
    onclick="delmat(${item.id_usuario}, ${idcurso}, '${item.email}')"
    <i class="fa-solid fa-trash"></i>
</button>
    </td>
</tr>
`;
                    });
                }
            }).then(async result => {

                if (result.isConfirmed) {

                    busca = new FormData();
                    busca.append("id", id);

                    const res = await fetch("control/buscaT.php", {
                        method: "POST",
                        body: busca,
                        credentials: "include"
                    })

                    const dados = await res.json();

                    const nome = dados.treinamentos[0].nome;


                    Swal.fire({
                        title: `
        <div style="display:flex; align-items:center; gap:12px; justify-content:center;">
            <i class="fa-solid fa-graduation-cap" style="font-size:34px; color:#3085d6;"></i>
            <span style="font-weight:700;">Cadastrar Usuário no Curso</span>
        </div>
    `,
                        icon: "info",
                        width: "550px",
                        html: `
        <div style="text-align:left; font-size:16px; padding:10px;">

            <!-- SELECT DE USUÁRIO -->
            <div style="margin-bottom:25px;">
                <label style="font-weight:600; display:flex; align-items:center; gap:8px; font-size:15px;">
                    <i class="fa-solid fa-users"></i> Selecionar Usuário
                </label>

                <div style="position:relative;">
                    <i class="fa-solid fa-user-circle"
                        style="position:absolute; left:12px; top:13px; font-size:18px; color:#555;"></i>

                    <select id="userSelect" class="swal2-input"
                        style="padding-left:40px; height:48px;">
                        <option value="">Selecione...</option>
                        ${dados.usuarios.map(u => `
                            <option value="${u.id}">
                                (${u.email})
                            </option>
                        `).join("")}
                    </select>

                    <i class="fa-solid fa-caret-down"
                        style="position:absolute; right:12px; top:18px; color:#777; pointer-events:none;"></i>
                </div>
            </div>

            <!-- INPUT NOME DO CURSO -->
            <div>
                <label style="font-weight:600; display:flex; align-items:center; gap:8px; font-size:15px;">
                    <i class="fa-solid fa-book"></i> Nome do Curso
                </label>

                <div style="position:relative;">
                    <i class="fa-solid fa-chalkboard-teacher"
                        style="position:absolute; left:12px; top:13px; font-size:18px; color:#555;"></i>

                    <input id="inpNome" class="swal2-input"
                        value="${nome}" disabled
                        style="padding-left:42px; height:48px; background:#f1f1f1; font-weight:600;">
                </div>
                
            </div>
            
            

        </div>
    `,
                        showCancelButton: true,
                        cancelButtonText: "Cancelar",
                        confirmButtonText: "Cadastrar",
                        confirmButtonColor: "#3085d6",
                    }).then(async result => {

                        if (!result.isConfirmed) return;

                        const usuario = document.getElementById("userSelect").value;
                        const idcurso = id;

                        const cadcurso = new FormData();
                        cadcurso.append("usuario", usuario);
                        cadcurso.append("idcurso", idcurso);

                        const res = await fetch("control/cadAocurso.php", {
                            method: "POST",
                            credentials: "include",
                            body: cadcurso,
                        });

                        const dados = await res.json();

                        if (dados.sucesso) {
                            Swal.fire({
                                icon: "success", 
                                title: "Usuário Cadastrado",
                                html: `O usuário foi cadastrado ao curso`,
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }else if(dados.EXISTE){
                            Swal.fire({
                                icon: "error", 
                                title: "Usuário Ja Cadastrado",
                                html: `O usuário ja esta cadastrado nesse curso`,
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                        else{
                            alert("errao")
                        }
                    })
                }
            })
        }
    });
}

function delmat(idUsuario, idCurso, emailUsuario) {

    Swal.fire({
        title: "Excluir Matrícula",
        html: `
            <p class="mb-3">Tem certeza que deseja excluir a matrícula deste usuário?</p>
            <p class="text-danger"><strong>Esta ação é irreversível!</strong></p>
            <label>Digite novamente para confirmar <i>${emailUsuario}</i><label>
            <input id="confirmEmail" type="email" class="swal2-input" placeholder="Digite o e-mail do usuário para confirmar">
        `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Excluir",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        preConfirm: () => {
            const emailDigitado = document.getElementById("confirmEmail").value.trim();

            if (emailDigitado === "") {
                Swal.showValidationMessage("Digite o e-mail para confirmar!");
                return false;
            }

            if (emailDigitado !== emailUsuario) {
                Swal.showValidationMessage("O e-mail não confere!");
                return false;
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {

            let dados = new FormData();
            dados.append("id_usuario", idUsuario);
            dados.append("id_curso", idCurso);

            fetch("control/delmat.php", {
                method: "POST",
                body: dados
            })
            .then(res => res.json())
            .then(resp => {
                if(resp.sucesso){
                Swal.fire({
                    icon: "success",
                    title: "Matrícula excluída!",
                    text: "O usuário foi removido do treinamento.",
                    confirmButtonColor: "#3085d6"
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: resp.erro || "Não foi possível remover a matrícula."
                });
            }
               
            })
            .catch(err => {
                Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: "Não foi possível remover a matrícula.",
                });
            });

        }
    });
}