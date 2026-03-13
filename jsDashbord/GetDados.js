getDados();

async function getDados() {
    const res = await fetch("dashbord/dados.php", {
        method: "GET",
        credentials: "include",
    });

    const dados = await res.json();

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