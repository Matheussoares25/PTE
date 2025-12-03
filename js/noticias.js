const token = localStorage.getItem('token');

if (!token) {
    alert("Você precisa realizar login primeiro");
    window.location.href = "index.html";
} else {
    const formData = new FormData();
    formData.append('token', token);

    fetch('control/verificatoken.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (!data.valido) {
            localStorage.removeItem('token');
            alert("login expirado, faça login novamente");
            window.location.href = "index.html";
        }
    });
}

function oflog(){
    localStorage.clear();
    window.location.href = "index.html";
}
Noticias();

async function Noticias(){
    try{
        const res = await fetch('control/noticias.php',{
            method: "POST",
            dataType: "json"
            
        })
        const dados = await res.json();

        const html = dados.map(n =>`
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">${n.titulo}</h5>
                    <p class="card-text">${n.conteudo}</p>
                    <p class="card-text"><small class="text-muted">${n.data_noticia}</small></p>
                </div>
            </div>
            `).join("");

        document.getElementById("ListaNoticias").innerHTML = html;





    }catch(error){
        console.log(error);
    }
}