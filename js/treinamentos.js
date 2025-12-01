 buscarTreinamentos();
    function buscarTreinamentos() {


        $(document).ready(function () {
            $.ajax({
                url: "control/buscarTreinamentos.php",
                method: "GET",
                dataType: "json",
                success: function (resposta) {

                    let html = "";

                    resposta.forEach(t => {
                        html += `
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-play-circle-fill text-secondary me-2" 
                           style="font-size: 1.7rem;"></i>

                        <div class="flex-grow-1 border-bottom pb-2">
                            <strong>${t.nome}</strong>
                        </div>

                        <a href="treinamento.php?id=${t.id}" 
                           class="btn btn-sm btn-primary ms-3">
                           Acessar
                        </a>
                    </div>
                `;
                    });

                    $("#listaTreinamentos").html(html);
                }
            });
        });
    }