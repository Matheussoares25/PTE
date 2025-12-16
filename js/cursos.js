document.addEventListener("DOMContentLoaded", carregarCursosSidebar);
function carregarCursosSidebar() {
    fetch("control/listarCursos.php")
        .then(res => res.json())
        .then(data => {
            const lista = document.getElementById("listaCursos");
            lista.innerHTML = "";

            if (!data.sucesso || data.cursos.length === 0) {
                lista.innerHTML = `
                    <li class="list-group-item text-center text-muted">
                        Nenhum curso encontrado
                    </li>`;
                return;
            }

            data.cursos.forEach(curso => {
                lista.innerHTML += `
                    <li class="list-group-item cursor-pointer" 
                        onclick="abrirCurso(${curso.id}, '${curso.nome}')">
                        <strong>
                            <i class="fa-solid fa-graduation-cap text-primary"></i>
                            ${curso.nome}
                        </strong>
                    </li>
                `;


                curso.modulos.forEach(mod => {

                    lista.innerHTML += `
                        <li class="list-group-item" 
                            style="padding-left:25px; cursor:pointer;"
                            onclick="abrirModulo(${mod.id_modulo}, '${mod.nome_modulo}')">
                            <i class="fa-solid fa-layer-group text-success"></i>
                            ${mod.nome_modulo}
                        </li>
                    `;


                    mod.aulas.forEach(aula => {
                        lista.innerHTML += `
        <li class="list-group-item"
            style="padding-left:45px; cursor:pointer;"
            onclick="abrirAula(${aula.id_aula}, '${aula.nome_aula}', ${mod.id_modulo})">
            <i class="fa-solid fa-circle-play text-danger"></i>
            ${aula.nome_aula ?? "Aula sem nome"}
        </li>
    `;
                    });
                });

                lista.innerHTML += "<hr>";
            });
        });
}





function abrirCurso(id, nome) {
    localStorage.setItem("idCurso", id);
    document.getElementById("cursoName").innerText = nome;

    document.getElementById("EditAula").style.display = "none";

    document.getElementById("AddModulo").style.display = "";
    document.getElementById("FormModulo").style.display = "";


    carregarModulosDoCurso(id);
}

async function abrirModulo(id, nome) {
    document.getElementById("cadCursos").style.display = "none";
    document.getElementById("EditAula").style.display = "none";
    localStorage.setItem("idModulo", id);
    localStorage.setItem("nomeModulo", nome);

    formdata = new FormData();
    formdata.append("idModulo", id);



    document.getElementById("NomeMod").innerHTML = "Editar Modulo" + "\n" + nome;
    document.getElementById("NameModuloe").value = nome;
    document.getElementById("EditModulo").style.display = "";

    const res = await fetch("control/listarAulas.php", {
        method: "POST",
        body: formdata,
        credentials: "include"
    });
    const dados = await res.json();
    console.log(dados);

    const html = dados.aulas.map(a => `
        <tr>
            <td>${a.nome_aula}</td>
            <td>${a.id ?? "Aula sem nome"}</td>
            <td>
                <button class="btn btn-primary" onclick="editarAula(${a.id_aula})">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <button class="btn btn-danger" onclick="excluirAula(${a.id_aula})"> 
                    <i class="fa-solid fa-trash"></i>
                </button>   
            </td>
        </tr>
    `).join("");
    document.getElementById("tabelaAulas").innerHTML = html;

}

async function abrirAula(id, nome, idModulo) {
    document.getElementById("cadCursos").style.display = "none";
    document.getElementById("EditModulo").style.display = "none";
    document.getElementById("EditAula").style.display = "";
    localStorage.setItem("idAula", id);
    localStorage.setItem("nomeAula", nome);
    localStorage.setItem("idModulo", idModulo);

    document.getElementById("nAula").innerHTML = "Editar Aula" + "\n" + nome ?? "SEM NOME ";
    document.getElementById("nomeAula").value = nome;
    console.log("teste")


    formdata = new FormData();
    formdata.append("idAula", id);


    const res = await fetch("control/dadosAula.php", {
        method: "POST",
        body: formdata,
        credentials: "include"
    });

    const dados = await res.json();


    console.log(dados)


    if (dados.sucesso && dados.dados?.conteudo) {
    document.getElementById("ResVideo").src = dados.dados.conteudo;
}


}

async function salvarAula() {
    const idAula = localStorage.getItem("idAula");
    const idModulo = localStorage.getItem("idModulo");
    const nomeAula = document.getElementById("nomeAula").value;
    const desc = document.getElementById("descricaoAula").value;
    

    console.log(desc);

    let formdata = new FormData();
    formdata.append("idAula", idAula);
    formdata.append("idModulo", idModulo);
    formdata.append("nomeAula", nomeAula);
    formdata.append("desc", desc);

    const fileVideo = document.getElementById("fileVideo").files[0];
    if (fileVideo) {
        formdata.append("video", fileVideo);
    }

    const res = await fetch("control/editarAula.php", {
        method: "POST",
        body: formdata,
        credentials: "include"
    });

    const dados = await res.json();

    if (dados.sucesso) {
        alert("Aula editada com sucesso");
        carregarCursosSidebar();
        console.log(nomeAula);

    } else {
        alert("Erro ao editar aula");
    }
}

async function cadCurso() {
    const nome = document.getElementById('NameCurso').value;

    formdata = new FormData();
    formdata.append("nome", nome);

    fetch("control/cadCurso.php", {
        method: "POST",
        body: formdata,
        credentials: "include"
    })
        .then(res => res.json())
        .then(data => {
            const id = data.dados.id;
            localStorage.setItem("idCurso", id);

            if (data.sucesso) {
                alert("Curso cadastrado com sucesso");
                carregarCursosSidebar();
                document.getElementById("AddModulo").style.display = "";
                document.getElementById("cursoName").innerHTML = data.dados.nome;
                return;

            } else if (data.Existe) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ja existe um curso com esse nome',
                    showConfirmButton: true,
                    confirmButtonText: 'Fechar',
                    backdrop: true,
                    scrollbarPadding: false
                })
                return;
            }
            else {
                alert("Erro ao cadastrar curso");
                return;
            }
        });


}

async function cadModulo() {
    Swal.fire({
    title: 'Criar módulo curso:',
    width: '900px',
    showConfirmButton: false,
    showCloseButton: true,
    html: `
        <div class="card card-custom">
            <div class="card-body p-4">

                <h3 id="NomeMod"></h3>

                <div class="mb-3 text-start">
                    <label class="form-label">Nome do Módulo</label>
                    <input type="text" class="form-control" id="NameModuloe"
                        placeholder="Ex: Introdução, Primeiros Conceitos, Parte 1...">
                    <button class="btn btn-success mt-2" id="btnEditarModulo">
                        Editar nome do módulo
                    </button>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-semibold">Aulas do Módulo</label>

                    <div class="table-responsive shadow-sm rounded">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-primary">
                                <tr>
                                    <th style="width: 50%">Nome da Aula</th>
                                    <th style="width: 15%">ID</th>
                                    <th style="width: 20%">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="tabelaAulas"></tbody>
                        </table>
                    </div>
                </div>

                <button class="btn btn-success mb-3" id="btnAddAula">
                    Adicionar Aula
                </button>

                <hr>

                <h5 class="mb-3">
                    <button onclick="criaModulo()" class="btn btn-success"> Salvar</button>
                </h5>

                <ul class="list-group" id="lista-modulos"></ul>

            </div>
        </div>
    `,
    didOpen: () => {
        // Eventos dentro do Swal
        document.getElementById('btnEditarModulo')
            .addEventListener('click', criaModulo);

        document.getElementById('btnAddAula')
            .addEventListener('click', criaAula);
    }
});


    // const nome = document.getElementById('NameModulo').value;
    // const idCurso = localStorage.getItem("idCurso");

    // formdata = new FormData();
    // formdata.append("idCurso", idCurso);
    // formdata.append("nome", nome);

    // fetch("control/cadModulo.php", {
    //     method: "POST",
    //     body: formdata,
    //     credentials: "include"
    // })
    //     .then(res => res.json())
    //     .then(data => {
    //         const idModulo = data.dados.id;
    //         localStorage.setItem("idModulo", idModulo);

    //         if (data.sucesso) {
    //             alert("Modulo cadastrado com sucesso");
    //             carregarCursosSidebar();
    //             document.getElementById("FormModulo").style.display = "";
    //             return;
    //         } else if (data.Existe) {
    //             Swal.fire({
    //                 icon: 'warning',
    //                 title: 'Ja existe um modulo com esse nome',
    //                 showConfirmButton: true,
    //                 confirmButtonText: 'Fechar',
    //                 backdrop: true,
    //                 scrollbarPadding: false
    //             })
    //             return;
    //         }
    //         else {
    //             alert("Erro ao cadastrar modulo");
    //             return;
    //         }
    //     });
}

async function criaModulo() {
    

    let nome;

    const idCurso = localStorage.getItem("idCurso");
    const idModulo = localStorage.getItem("idModulo");
    nome = document.getElementById('NameModulo').value;

    if (!nome) {
        nome = document.getElementById('NameModuloe').value;
    }
    const quantiade = document.getElementById('qtdAulas').value;

    console.log(idCurso, idModulo, nome, quantiade);
    formdata = new FormData();
    formdata.append("idCurso", idCurso);
    formdata.append("idModulo", idModulo);
    formdata.append("nome", nome);
    formdata.append("qtd", quantiade);

    const res = await fetch("control/criaModulo.php", {
        method: "POST",
        body: formdata,
        credentials: "include"
    });
    const dados = await res.json();

    if (dados.sucesso) {

        carregarCursosSidebar();

        document.getElementById("FormModulo").style.display = "";
        return;
    }
    else {
        alert("Erro ao criar modulo");
        return;
    }
}
