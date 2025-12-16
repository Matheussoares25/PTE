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
    document.getElementById("cadastroCurso").style.display = "none";
    document.getElementById("EditModulo").style.display = "none"

    document.getElementById("FormModulo").style.display = "";




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
                <button class="btn btn-primary"  onclick="abrirAula(${a.id}, '${a.nome_aula}', '${a.id_modulo}')">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <button class="btn btn-danger" onclick="excluirAula(${a.id}, '${a.nome_aula}')"> 
                    <i class="fa-solid fa-trash"></i>
                </button>   
            </td>
        </tr>
    `).join("");
    document.getElementById("tabelaAulas").innerHTML = html;

}

async function abrirAula(id, nome, idModulo) {
    document.getElementById("ResVideo").src = "";
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


    if (dados?.dados?.desc_midia) {
        document.getElementById("descAtual").innerText = dados.dados.desc_midia;
    } else {
        document.getElementById("descAtual").innerText = "";
    }






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

    if (nomeAula === "null") {
        alert("Preencha o nome da aula");
        return;
    }


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

async function criaUmaAula() {
    const idModulo = localStorage.getItem("idModulo");

    const formdata = new FormData();
    formdata.append("idModulo", idModulo);

    const res = await fetch("control/criaUmaAula.php", {
        method: "POST",
        body: formdata,
        credentials: "include"
    });
    const dados = await res.json();

    if (dados.sucesso) {
        carregarCursosSidebar();
        Swal.fire({
            icon: 'success',
            title: 'Aula Criada com sucesso',
            showConfirmButton: false,
            timer: 1500
        })
    }
}

async function cadCurso() {
    const nome = document.getElementById('NameCurso').value;

    if (nome == "") {
        Swal.fire({
            title: `Preencha Todos Campos`,
            html: `Preencha o campo com nome do curso`,
            icon: 'error'
        });
        return;
    } else { }

    formdata = new FormData();
    formdata.append("nome", nome);

    fetch("control/cadCurso.php", {
        method: "POST",
        body: formdata,
        credentials: "include"
    })
        .then(res => res.json())
        .then(data => {



            if (data.sucesso) {
                Swal.fire({
                    title: 'Curso Adicionado com Sucesso',
                    html: 'Os proximos passos é adicionar modulos e aulas',
                    icon: 'success'
                })
                const id = data.dados.id;
                localStorage.setItem("idCurso", id);
                carregarCursosSidebar();

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
        title: `Criar módulo curso`,
        width: '900px',
        showConfirmButton: true,
        showCloseButton: true,
        confirmButtonText: "salvar",
        html: `
        <div class="card card-custom">
            <div class="card-body p-4">

                <h3 id="NomeMod"></h3>

                <div class="mb-3 text-start">
                    <label class="form-label">Nome do Módulo</label>
                    <input type="text" class="form-control" id="nameismod" 
                        placeholder="Ex: Introdução, Primeiros Conceitos, Parte 1...">
                    <button type="button" class="btn btn-success mt-2" id="btnEditarModulo">
                        Editar nome do módulo
                    </button>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label">Quantidade de aulas</label>
                    <input type="number" class="form-control" id="qtdAulass"
                    placeholder="Ex: 5" min="1">
                </div>
                <hr>

                <ul class="list-group" id="lista-modulos"></ul>

            </div>
        </div>
    `,
        preConfirm: async () => {
            const nomeModulo = document.getElementById("nameismod").value;
            const qtdAulas = document.getElementById("qtdAulass").value.trim();
            const idCurso = localStorage.getItem('idCurso');

            formdata = new FormData();
            formdata.append("nome", nomeModulo);
            formdata.append("qtd", qtdAulas);
            formdata.append('idCurso', idCurso);

            if (nomeModulo == "") {
                Swal.showValidationMessage("Informe o nome do módulo");
                return false;
            }


            if (!qtdAulas || qtdAulas <= 0) {
                Swal.showValidationMessage("Informe a quantidade de aulas");
                return false;
            }

            try {
                const res = await fetch("control/criaModulo.php", {
                    method: "POST",
                    body: formdata,
                    credentials: "include"

                });

                const data = await res.json();

                if (!data.success) {
                    throw new Error(data.message);
                }

                return data;

            } catch (err) {
                Swal.showValidationMessage(err.message);
                return false;
            }
        }

    })



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
        Swal.fire({
            title: 'Modulo cadastrado com Sucesso',
            html: '<strong>O modulo Foi cadastrado com sucesso, junto das aulas, agora basta editar as informações das aulas</strong>',
            icon: 'success'
        })
        carregarCursosSidebar();

        document.getElementById("FormModulo").style.display = "";
        return;
    }
    else {
        alert("Erro ao criar modulo");
        return;
    }
}

async function excluirAula(id, nome) {
    Swal.fire({
        title: `Excluir Aula ${nome}`,
        html: `
            <p class="mb-3">Tem certeza que deseja excluir a aula deste curso?</p>
            <p class="text-danger"><strong>Esta ação é irreversível!</strong></p>
            <label>Digite novamente para confirmar <i>${nome}</i><label>
            <input id="confirmNome" type="text" class="swal2-input" placeholder="Digite o nome do curso para confirmar">
        `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Excluir",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        preConfirm: () => {
            const nomeDigitado = document.getElementById("confirmNome").value.trim();

            if (nomeDigitado === "") {
                Swal.showValidationMessage("Digite o nome da aula para confirmar!");
                return false;
            }

            if (nomeDigitado !== nome) {
                Swal.showValidationMessage("O nome da aula não confere!");
                return false;
            }
        }
    }).then(async (result) => {
        if (result.isConfirmed) {

            const formdata = new FormData();
            formdata.append("id", id);

            const res = await fetch("control/exAula.php", {
                method: "POST",
                body: formdata,
                credentials: "include"
            });

            const dados = await res.json();

            if (dados.sucesso) {
                Swal.fire({
                    title: 'Aula excluída com sucesso',
                    html: 'A aula foi excluída com sucesso',
                    icon: 'success'
                });
                carregarCursosSidebar();
                abrirModulo(localStorage.getItem("idModulo"), localStorage.getItem("nomeModulo"));
            }
        }
    });
}
