
if (document.getElementById("login") != null) {

    document.getElementById("login").addEventListener("submit", async function (event) {
        event.preventDefault();

        const nome = $('#Email').val();
        const senha = $('#senha').val();
        

        const formData = new FormData();
        formData.append("email", nome);
        formData.append("senha", senha);

        try {
            const response = await fetch('control/verificalogin.php', {
                method: 'POST',
                body: formData,
                credentials: "include"
            });

            const data = await response.json();

            if(data.PACESS){
                alert("teste");
            }


            if (data.success) {
                $('#Resposta').html('<p>Login bem-sucedido</p>');
                localStorage.setItem("token", data.token);
                localStorage.setItem("idUser", data.id);
                localStorage.setItem("tipoUsuario", data.tipo);

                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    },
                    didClose: () => {
                        window.location.href = "noticias.html";
                    }
                });

                Toast.fire({
                    icon: "success",
                    title: "Signed in successfully"
                });

            } else if (data.NAOEXISTE) {
                Swal.fire({
                    icon: 'error',
                    title: 'Usuário Não Encontrado',
                    html: 'Deseja cadastrar esse usuário?',
                    showConfirmButton: true,
                    confirmButtonText: 'Sim',
                    showDenyButton: true,
                    denyButtonText: 'Nao',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "cadastrar.html";
                    } else {
                        location.reload();
                    }
                });

            } else if (data.serrada) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de login',
                    html: 'usuario ou senha incorretos',
                    showConfirmButton: true,
                });

            }else {
                $('#Resposta').html('<p>Erro ao fazer login</p>');
            }

        } catch (error) {
            $('#Resposta').html('<p>Ocorreu um erro na requisição.</p>');
            console.error(error);
        }
    });
}


if (document.getElementById("cadastro") != null) {

    document.getElementById("cadastro").addEventListener("submit", async function (event) {
        event.preventDefault();

        const digSenha = $('#senha').val();
        const checkSenha = $('#confirmasenha').val();

        if (digSenha !== checkSenha) {
            alert("Falha na confirmação de senha");
            return;
        }

        const email = $('#email').val();
        const nome = $('#nome').val();
        const senha = $('#senha').val();
        const upFoto = $('#foto')[0].files[0];

        if (email === "") {
            alert("Insira um email");
            return;
        }

        const formData = new FormData();
        formData.append("email", email);
        formData.append("nome", nome);
        formData.append("senha", senha);
        formData.append("foto", upFoto);

        Swal.fire({
            icon: 'alert',
            title: 'Termos de uso PTE',
            html: `<div style="text-align: left; max-height: 300px; overflow-y: auto; padding-right: 10px;">

    <h3 style="margin-top: 0;">Termos de Uso</h3>

    <p>
        Ao utilizar este sistema, você concorda com os termos e condições descritos abaixo. 
        O objetivo é garantir segurança, organização e bom uso da plataforma.
    </p>

    <h4>1. Coleta e Uso de Dados</h4>
    <p>
        <strong>1.1</strong> O sistema pode coletar informações como nome, e-mail, foto e registros de acesso.<br>
        <strong>1.2</strong> Esses dados são usados exclusivamente para identificação do usuário e funcionamento adequado do sistema.
    </p>

    <h4>2. Responsabilidades do Usuário</h4>
    <ul>
        <li>Não compartilhar sua senha com terceiros.</li>
        <li>Manter informações pessoais atualizadas e verdadeiras.</li>
        <li>Usar o sistema apenas para fins legais e autorizados.</li>
    </ul>

    <h4>3. Segurança</h4>
    <p>
        <strong>3.1</strong> O sistema utiliza medidas de proteção para manter os dados seguros.<br>
        <strong>3.2</strong> Ainda assim, nenhum sistema é 100% seguro; o usuário concorda com os riscos presentes na internet.
    </p>

    <h4>4. Alterações nos Termos</h4>
    <p>
        <strong>4.1</strong> Os termos podem ser atualizados a qualquer momento, sem aviso prévio.<br>
        <strong>4.2</strong> O uso contínuo do sistema indica aceitação das mudanças.
    </p>

    <h4>5. Aceite</h4>
    <p>
        Ao clicar em <strong>“Aceito”</strong>, você confirma que leu, entendeu e concorda com todos os termos aqui apresentados.
    </p>

</div>`,
            width: '60%',
            showConfirmButton: true,
            confirmButtonText: 'Aceito',
            showCancelButton: true,
            cancelButtonText: 'Nao aceito',
            backdrop: true,
            scrollbarPadding: false,
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch('control/cadastro.php', {
                        method: 'POST',
                        body: formData,
                        credentials: "include"
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cadastro realizado com sucesso',
                            showConfirmButton: true,
                            confirmButtonText: 'Fechar',
                            backdrop: true,
                            scrollbarPadding: false,
                        });
                    } else if (data.Existe) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Usuario ja cadastrado',
                            html: 'Ir para tela de login?',
                            showConfirmButton: true,
                            showCancelButton: true,
                            backdrop: true,
                            scrollbarPadding: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "index.html";
                            }
                        });

                    } else if (data.mensagem) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Nenhuma foto cadastrada',
                            html: 'Preencha o campo foto',
                            showConfirmButton: true,
                            confirmButtonText: 'Fechar',
                            backdrop: true,
                            scrollbarPadding: false,
                        });
                    } else {
                        alert("Falha ao cadastrar usuario");
                    }

                } catch (error) {
                    $('#Resposta').html('<p>Ocorreu um erro na requisição</p>');
                    console.error(error);
                }

            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Voce precisa concordar com os termos de uso',
                    showConfirmButton: true,
                    confirmButtonText: 'Fechar',
                    backdrop: true,
                    scrollbarPadding: false,
                });
            }
        });
    });
}



async function oflog() {
    Swal.fire({
        icon: 'warning',
        title: 'Deseja realmente sair?',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Nao',
        backdrop: true,
        scrollbarPadding: false
    }).then(async (result) => {
        if (result.isConfirmed) {
            await fetch("control/logout.php", {
                method: "POST",
                credentials: "include"
            });
            localStorage.removeItem("token");
            localStorage.removeItem("idUser");
            localStorage.removeItem("tipoUsuario");
            localStorage.clear();
            window.location.href = "index.html";
        }
    })

  
}





