getDados();

async function getDados() {
    const res = await fetch("dashbord/dados.php", {
        method: "GET",
        credentials: "include",
    });

    const dados = await res.json();

    if(dados.Negado){
       Swal.fire({
          icon: "warning",
          title:"ACESSO NEGADO",
          html: "REALIZE LOGIN",
        });
        setTimeout(() =>{
          window.location.href = "index.html";

        },1200)
    
    }

    document.getElementById("qtdAlunos").innerHTML = dados.alunos;
    document.getElementById("qtdCursos").innerHTML = dados.cursos;
    document.getElementById("qtdMatriculas").innerHTML = dados.matriculas;
    document.getElementById("qtdProvas").innerHTML = dados.provas;

    const ctx = document.getElementById('graficoProvas');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Acertos', 'Erros'],
            datasets: [{
                data: [dados.acertagem, 100 - dados.acertagem],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });

}


async function openCriados() {


    Swal.fire({
        title: 'Cursos Criados',
        width: '800px',
        html: `
        <div class="d-flex justify-content-center">
            <table class="table table-bordered text-center w-75">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Status</th>
                        <th>Criado</th>
                    </tr>
                </thead>
                <tbody id="Tabela">
                </tbody>
            </table>
        </div>
        `,

        didOpen: async () => {
            try{
                const dados = await fetch('dashbord/dados.php',{
                    method: "GET",
                    credentials: "include",
                });

                const res = await dados.json();

                const tabela = document.getElementById('Tabela');

                tabela.innerHTML = res.tCursos.map(p => `
                
                <tr>
                    <td>${p.id}</td>
                    <td>${p.nome}</td>
                    <td>${p.status == 1 ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-danger">Inativo</span>'}</td>
                    <td>${p.criado}</td>    
                </tr>`  
            ).join('');
            }catch{
                document.getElementById("Tabela").innerHTML = `<tr><td colspan="4">Erro ao carregar dados</td></tr>`;

            }
        }

    });
    
}

async function openAlunos(){
    Swal.fire({
        title: 'Alunos em curso',
        width: '800px',
        html:  `
    <div class="table-responsive">
        <table class="table table-sm table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Matrícula</th>
                    <th>ID Usuário</th>
                    <th>Nome Usuario</th>
                    <th>Curso</th>
                    <th>status/Matricula</th>
                    <th>Início</th>
                    <th>Módulo</th>
                </tr>
            </thead>
            <tbody id="TabelaMatriculas">
            
            </tbody>
        </table>
    </div>
    `,

    didOpen: async () => {
        try{
            const dados = await fetch('dashbord/dados.php',{
                method: "GET",
                credentials: "include"
            })

            const res = await dados.json();

            const tabela = document.getElementById('TabelaMatriculas');


            tabela.innerHTML = res.tAlunos.map(p => `
                <tr>
                    <th>${p.matricula}</th>
                    <th>${p.id_usuario}</th>
                    <th>${p.nome_usuario}</th>
                    <th>${p.nome_curso}</th>
                    <th>${p.status_curso == 1 ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-danger">Inativo</span>'}</td>
                    <th>${p.data_curso.split(' ')[0].split('-').reverse().join('/')}</th>
                `).join('');

            
        }catch{
            tabela.innerHTML = "Erro ao carregar dados";
        }
    }

    })
}

async function openProvas(){
    Swal.fire({
         title: 'Provas Realizadas',
        width: '800px',
        html:  `
    <div class="table-responsive">
        <table class="table table-sm table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Nome Prova</th>
                    <th>Realizada por</th>
                    <th>Nº de Questões</th>
                    <th>Acertos</th>
                    <th>% de Acerto</th>
                    <th>Realizada em:</th>
                    <th>Módulo</th>
                </tr>
            </thead>
            <tbody id="TabelaProvas">
            
            </tbody>
        </table>
    </div>
    `,

    didOpen: async () => {
     try{
        const dados = await fetch('dashbord/dados.php',{
            method: "GET",
            credentials: "include",
        })

        const res = await dados.json();

        const tabela = document.getElementById("TabelaProvas");

        tabela.innerHTML = res.dProvas.map(p => `
            <tr>
                    <td >${p.nome_prova}</td>
                    <td id="btMiniDash" onclick="provaUse(${p.id_user})" style="cursor: pointer"><i class="fa-solid fa-info" style="color: rgb(0, 143, 255);"></i> ${p.nome_usuario}</td>
                    <td>${p.qtd_questoes}</td>
                    <td>${p.acertos}</td>
                    <td>${p.porcentagem}</td>
                    <td>${p.data_conclusao.split(' ')[0].split('-').reverse().join('/')}<th>
                    
                `).join('');
         

     }catch{}
    }
    })
}