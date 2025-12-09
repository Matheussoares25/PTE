ranking();

async function ranking() {
    const res = await fetch("control/ranking.php",{
        method: "POST",
        credentials: "include"
    });
    const dados = await res.json();
    console.log(dados);

   
    const html = dados.qnt.map(d => `
        <tr>
            <td>${d.nome_usuario}</td>
            <td>${d.total_cursos}</td>
        </tr>
    `).join("");

    document.getElementById("Tableranking").innerHTML = html;

   
    const top1 = dados.qnt[0] ? dados.qnt[0].nome_usuario : "—";
    const top2 = dados.qnt[1] ? dados.qnt[1].nome_usuario : "—";
    const top3 = dados.qnt[2] ? dados.qnt[2].nome_usuario : "—";


    document.getElementById("top1").innerHTML = top1;
    document.getElementById("top2").innerHTML = top2;
    document.getElementById("top3").innerHTML = top3;

    document.getElementById("imgTop1").src = dados.qnt[0] ? dados.qnt[0].Foto : "placeholder.jpg";
    document.getElementById("imgTop2").src = dados.qnt[1] ? dados.qnt[1].Foto : "placeholder.jpg";
    document.getElementById("imgTop3").src = dados.qnt[2] ? dados.qnt[2].Foto : "placeholder.jpg";
}
