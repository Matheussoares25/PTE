
document.addEventListener('click', () => {
    checkLogin();
});

ranking();

async function ranking() {

    const cargo = localStorage.getItem("tipoUsuario");

    if (cargo != 2) {
        document.querySelectorAll(".btnadm").forEach((el) => {
            el.style.display = "none";
        });
    }

    const res = await fetch("routes/api.php?acao=buscarRanking", {
        method: "POST",
        credentials: "include"
    });
    const dados = await res.json();
    console.log(dados);


    const html = dados.ranking.map(d => `
        <tr>
            <td>${d.nome}</td>
            <td>${d.total_notas}</td>
        </tr>
    `).join("");

    document.getElementById("Tableranking").innerHTML = html;


    const top1 = dados.ranking[0] ? dados.ranking[0].nome : "—";
    const top2 = dados.ranking[1] ? dados.ranking[1].nome : "—";
    const top3 = dados.ranking[2] ? dados.ranking[2].nome : "—";


    document.getElementById("top1").innerHTML = top1;
    document.getElementById("top2").innerHTML = top2;
    document.getElementById("top3").innerHTML = top3;

    document.getElementById("imgTop1").src = dados.ranking[0] ? dados.ranking[0].Foto : "placeholder.jpg";
    document.getElementById("imgTop2").src = dados.ranking[1] ? dados.ranking[1].Foto : "placeholder.jpg";
    document.getElementById("imgTop3").src = dados.ranking[2] ? dados.ranking[2].Foto : "placeholder.jpg";
}
