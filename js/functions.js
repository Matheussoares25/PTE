
document.getElementById("loginForm").addEventListener("submit", async function (event) {
    event.preventDefault();


    const nome = $('#Email').val();
    const senha = $('#senha').val();



    const formData = new FormData();
    formData.append("email", nome),
        formData.append("senha", senha)


    $.ajax({
        type: 'POST',
        url: 'control/verificalogin.php',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (response) {
            if (response.success) {

                $('#Resposta').html('<p>Login bem-sucedido</p>');
                window.location.href = "logado.html";
                return;

            } else {
                $('#Resposta').html('<p>Usuário ou senha incorretos</p>');
                return;
            }
        },
        error: function () {
            $('#Resposta').html('<p>Ocorreu um erro na requisição.</p>');
        }
    });
});

document.getElementById("cadastro").addEventListener("submit", async function (event) {
        event.preventDefault();

        //verifico se as senhas são iguais: 

        const digSenha = $('#senha').val();
        const checkSenha = $('#confirmasenha').val();


        if (digSenha !== checkSenha) {
            alert("Falha na confimação de senha");
            return;
        }



        const email = $('#email').val();
        const senha = $('#senha').val();
        const upFoto = $('#foto')[0].files[0];


        if (email === "") {
            alert("insira um email");
            return;
        }


        const formData = new FormData();
        formData.append("email", email);
        formData.append("senha", senha);
        formData.append("foto", upFoto);



        $.ajax({
            type: 'POST',
            url: 'control/cadastro.php',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'Json',
            success: function (response) {
                if (response.success) {
                    $('#Resposta').html('<p>Usuario cadastrado com sucesso</P>');
                    return;

                } else if (response.Existe) {
                    alert("usuario ja cadastrado");
                } else if (response.mensagem) {
                    alert("nenhuma foto cadastrada");
                } else {
                    alert("Falha ao cadastrar usuario");
                }

            },
            error: function () {
                $('#Resposta').html('<p>Ocorreu um erro na requisição</P>');
            }
        });

    });
