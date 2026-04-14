
window.onload = function () {
    if (this.localStorage.getItem("tipoUsuario") == 1) {
        document.getElementById("UserComum").style.display = "block";
        document.getElementById("UserAdm").style.display = "none";
        document.querySelectorAll(".btnadm").forEach(el => {
            el.style.display = "none";
        });
    } else {
        document.getElementById("UserComum").style.display = "none";
        document.getElementById("UserAdm").style.display = "block";
    }

}
geral();

function geral() {
const fotoPerfil = document.getElementById("FotoUser");

if (fotoPerfil) {
    fotoPerfil.src = localStorage.getItem("fotoUser") || "semFoto.jpg";
}

const li = document.getElementById("listOpcoes");

if (li) {
    li.innerHTML = `
        <a class="dropdown-item" href="#" onclick="perfil()">Perfil</a>
        <a class="dropdown-item" href="#" onclick="report()">Problemas</a>
        <a class="dropdown-item" href="#" onclick="oflog()">Sair</a>
    `;
}

}

async function report() {
    Swal.fire({
        icon: 'warning',
        width: "1500px",
        input: "text",
        title: 'Relatar um problema',
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Enviar',

        didOpen: () => {
            const resposta = Swal.getPopup().querySelector('input');
            
            
        }

    })
    
}


if (document.getElementById("login") != null) {

    document.getElementById("login").addEventListener("submit", async function (event) {
        event.preventDefault();

        const nome = $('#Email').val();
        const senha = $('#senha').val();


        const formData = new FormData();
        formData.append("email", nome);
        formData.append("senha", senha);

        const res = await fetch("routes/api.php?acao=aceitarTermos", {
            method: "POST",
            body: formData,
            credentials: "include"
        });
        const date = await res.json();

        if (date.PACESS) {
            Swal.fire({
                icon: 'error',
                title: 'TERMOS E CONDICOES',
                width: "980px",
                html: `<div style="text-align: left; max-height: 300px; overflow-y: auto; padding-right: 10px; line-height: 1.5;">

<p>
O PTE – Portal de Treinamento e Evolução, na qualidade de Controlador de dados pessoais, manifesta o seu compromisso inabalável com a proteção da privacidade e com o tratamento adequado das informações pessoais de todos os titulares que acessam, navegam ou utilizam a sua plataforma digital de treinamentos, avaliações de desempenho e desenvolvimento profissional.
Esta Política de Privacidade e Proteção de Dados Pessoais foi elaborada em estrita observância à Lei Geral de Proteção de Dados Pessoais (LGPD) – Lei nº 13.709, de 14 de agosto de 2018, bem como às demais normas complementares e regulamentares emitidas pela Autoridade Nacional de Proteção de Dados (ANPD). O documento tem por objetivo estabelecer, de forma clara, transparente e detalhada, as regras aplicáveis à coleta, ao tratamento, ao armazenamento, ao compartilhamento e à eliminação dos dados pessoais, garantindo o pleno exercício dos direitos dos titulares e o cumprimento das obrigações legais impostas ao Controlador.
Ao acessar ou utilizar qualquer funcionalidade da plataforma, o usuário declara ter lido, compreendido e aceitado integralmente os termos aqui dispostos. Caso não concorde com qualquer disposição, recomenda-se a abstenção do uso dos serviços oferecidos.
</p>

<h4>1. Dados Pessoais Coletados</h4>
<p>
O PTE coleta dados pessoais estritamente necessários ao fornecimento dos serviços, observados os princípios da finalidade, adequação, necessidade, transparência, segurança e não discriminação previstos no art. 6º da LGPD.
As categorias de dados incluem, de forma exemplificativa e não exaustiva:
</p>

<p><strong>I.</strong> Dados fornecidos diretamente pelo titular: nome completo, endereço eletrônico, número de inscrição no CPF, RG, telefone de contato, cargo profissional, denominação da empresa empregadora, respostas a questionários, avaliações de desempenho, feedbacks qualitativos, certificados emitidos, dados de autenticação (login e senha) e quaisquer outras informações voluntariamente inseridas durante o cadastro ou interação com a plataforma.</p>

<p><strong>II.</strong> Dados coletados automaticamente: endereço de protocolo de internet (IP), tipo e versão do navegador, sistema operacional, dispositivo de acesso (computador, smartphone ou tablet), páginas visitadas, tempo de permanência, interações realizadas, histórico de navegação e demais informações obtidas por meio de cookies, pixel tags, beacons e tecnologias análogas.</p>

<p><strong>III.</strong> Dados relacionados ao desempenho profissional: quando o titular for colaborador de empresa cliente, o PTE poderá tratar informações vinculadas a treinamentos realizados, métricas de evolução, notas de avaliação, relatórios de engajamento e indicadores de desenvolvimento, sempre em conformidade com as bases legais da LGPD e com o legítimo interesse das partes envolvidas no contrato de prestação de serviços.</p>

<h4>2. Finalidades e Bases Legais do Tratamento</h4>
<p>
O tratamento dos dados pessoais é realizado com finalidade específica, explícita e legítima, nos exatos termos do art. 7º da LGPD. As operações de tratamento destinam-se exclusivamente a:
</p>

<p><strong>I.</strong> Viabilizar o acesso à plataforma, à oferta de conteúdos educativos e às ferramentas de capacitação profissional;</p>
<p><strong>II.</strong> Executar avaliações de desempenho, acompanhar o progresso dos usuários, gerar certificados e produzir relatórios de evolução individual ou corporativa;</p>
<p><strong>III.</strong> Aperfeiçoar continuamente os serviços, personalizar a experiência do usuário e desenvolver novas funcionalidades alinhadas às necessidades do mercado;</p>
<p><strong>IV.</strong> Cumprir obrigações legais, regulatórias e contratuais assumidas perante o titular ou perante as empresas contratantes;</p>
<p><strong>V.</strong> Enviar comunicações operacionais e informativas estritamente relacionadas à plataforma, vedada qualquer forma de envio não solicitado.</p>

<p><strong>As bases legais que legitimam o tratamento compreendem:</strong></p>

<p><strong>I.</strong> Execução de contrato ou de procedimentos preliminares contratuais (art. 7º, II);</p>
<p><strong>II.</strong> Cumprimento de obrigação legal ou regulatória (art. 7º, I);</p>
<p><strong>III.</strong> Exercício regular de direitos em processo judicial, administrativo ou arbitral (art. 7º, VI);</p>
<p><strong>IV.</strong> Legítimo interesse do Controlador, devidamente sopesado com os direitos e liberdades fundamentais do titular (art. 7º, IX);</p>
<p><strong>V.</strong> Consentimento expresso do titular, quando exigido para finalidades acessórias ou não amparadas pelas demais bases (art. 7º, I).</p>

<h4>3. Compartilhamento de Dados Pessoais</h4>
<p>
O compartilhamento de dados ocorre de forma restrita, necessária e sempre acompanhada de salvaguardas contratuais que garantem o mesmo nível de proteção exigido pela LGPD. Não se realiza divulgação pública ou compartilhamento indiscriminado de dados de identificação pessoal.
O compartilhamento limita-se às seguintes hipóteses:
</p>

<p><strong>I.</strong> Com empresas clientes ou parceiras, quando o titular for colaborador e houver solicitação formal de relatórios de desempenho ou evolução profissional;</p>
<p><strong>II.</strong> Com operadores e prestadores de serviços essenciais (hospedagem em nuvem, ferramentas de envio de e-mail, Google AdSense, processadores de pagamento e consultorias especializadas), os quais atuam sob instruções expressas do Controlador e estão vinculados por cláusulas contratuais de confidencialidade e proteção de dados;</p>
<p><strong>III.</strong> Quando exigido por determinação legal, ordem judicial ou requisição de autoridade competente, inclusive a ANPD.</p>

<h4>4. Cookies e Tecnologias de Rastreamento</h4>
<p>
A plataforma utiliza cookies, identificadores digitais e tecnologias correlatas para otimizar a experiência do usuário, analisar o tráfego, medir o desempenho dos conteúdos e viabilizar a exibição de publicidade relevante.
Especificamente, o serviço Google AdSense pode empregar cookies (inclusive o DoubleClick) para rastreamento anônimo de interesses, limitação da frequência de anúncios e entrega de publicidade comportamental. Cookies de afiliados igualmente são utilizados para identificar acessos originados de sites parceiros, permitindo o devido creditamento.
O titular poderá gerenciar as preferências de cookies diretamente nas configurações do navegador. A desativação de determinados cookies, contudo, poderá comprometer a plena funcionalidade da plataforma.
</p>

<h4>5. Armazenamento, Retenção e Segurança dos Dados</h4>
<p>
Os dados pessoais são armazenados pelo período estritamente necessário ao cumprimento das finalidades informadas ou ao atendimento de obrigações legais, contratuais ou regulatórias. Findo o prazo, os dados são eliminados ou anonimizados de forma segura.
O Controlador adota medidas de segurança técnicas, administrativas, físicas e organizacionais consideradas adequadas ao estado da arte, tais como criptografia de dados em trânsito e em repouso, controle rigoroso de acessos, firewalls, monitoramento contínuo, auditorias periódicas, treinamentos regulares da equipe e políticas internas de confidencialidade, com o intuito de prevenir incidentes de violação de dados.
</p>

<h4>6. Transferência Internacional de Dados</h4>
<p>
Eventuais transferências internacionais de dados (por exemplo, para servidores do Google ou outros provedores globais) ocorrem somente para países que ofereçam grau adequado de proteção ou mediante cláusulas contratuais específicas aprovadas pela ANPD, garantindo a observância dos princípios e direitos previstos na LGPD.
</p>

<h4>7. Direitos dos Titulares dos Dados (Art. 18 da LGPD)</h4>
<p>
O titular dos dados pessoais possui, em relação ao Controlador, os seguintes direitos:
</p>

<p><strong>I.</strong> Confirmação da existência de tratamento;</p>
<p><strong>II.</strong> Acesso aos dados tratados;</p>
<p><strong>III.</strong> Correção de dados incompletos, inexatos ou desatualizados;</p>
<p><strong>IV.</strong> Anonimização, bloqueio ou eliminação de dados desnecessários;</p>
<p><strong>V.</strong> Portabilidade dos dados;</p>
<p><strong>VI.</strong> Eliminação dos dados tratados com base no consentimento;</p>
<p><strong>VII.</strong> Informação sobre compartilhamento;</p>
<p><strong>VIII.</strong> Revogação do consentimento;</p>
<p><strong>IX.</strong> Revisão de decisões automatizadas.</p>

<h4>8. Links para Sites de Terceiros</h4>
<p>
A plataforma pode conter links para sites externos. O PTE não exerce qualquer controle sobre o conteúdo ou políticas desses ambientes.
</p>

<h4>9. Compromisso do Usuário</h4>

<p><strong>I.</strong> Praticar atos legais e éticos;</p>
<p><strong>II.</strong> Não divulgar conteúdos ilegais;</p>
<p><strong>III.</strong> Não inserir vírus ou códigos maliciosos.</p>

<h4>10. Alterações na Política</h4>
<p>
O Controlador reserva-se o direito de modificar esta Política a qualquer momento.
</p>

<h4>11. Contato e Encarregado de Proteção de Dados (DPO)</h4>
<p>
Encarregado (DPO): André Matheus Fifty Jivanildo da Cunha<br>
E-mail geral: contato@pte.dev.br
</p>



</div>`,
                showConfirmButton: true,
                showCancelButton: true,
                cancelButtonText: 'Não Aceito',
                confirmButtonText: 'Aceito',

            }).then(async (result) => {
                if (result.isConfirmed) {
                    const response = await fetch('routes/api.php?acao=login', {
                        method: 'POST',
                        body: formData,
                        credentials: "include"
                    });

                    const data = await response.json();
                    if (data.success) {
                        $('#Resposta').html('<p>Login bem-sucedido</p>');
                        localStorage.setItem("token", data.token);
                        localStorage.setItem("idUser", data.id);
                        localStorage.setItem("tipoUsuario", data.tipo);
                        setTimeout(() => {
                            verificar();
                        }, 3000);

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

                    } else {
                        $('#Resposta').html('<p>Erro ao fazer login</p>');
                    }



                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'TERMOS E CONDICOES',
                        html: 'Voce precisa aceitar os termos e condicoes para continuar',
                        showConfirmButton: true,
                    })
                }
            })

        } else {


            try {
                const response = await fetch('routes/api.php?acao=login', {
                    method: 'POST',
                    body: formData,
                    credentials: "include"
                });

                const data = await response.json();


                if (data.success) {
                    $('#Resposta').html('<p>Login bem-sucedido</p>');
                    localStorage.setItem("token", data.token);
                    localStorage.setItem("idUser", data.id);
                    localStorage.setItem("tipoUsuario", data.tipo);
                    setTimeout(() => {
                        verificar();
                    }, 3000);



                    if (data.foto) {
                        localStorage.setItem("fotoUser", data.foto);
                    }

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

                } else {
                    $('#Resposta').html('<p>Erro ao fazer login</p>');
                }

            } catch (error) {
                $('#Resposta').html('<p>Ocorreu um erro na requisição.</p>');
                console.error(error);
            }
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
                    const response = await fetch('routes/api.php?acao=cadastrarUsuario', {
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





async function checkLogin() {


    const res = await fetch("routes/api.php?acao=ControleLogin", {
        method: "POST",
        credentials: "include"
    });

    const dados = await res.json();
    if (dados.EXPIRADO) {

        await Swal.fire({
            icon: 'warning',
            title: 'Sua sessão expirou',
            text: 'Redirecionando...',
            showConfirmButton: true,
            confirmButtonText: 'Fechar',
            timer: 8000,
            timerProgressBar: true,
            allowOutsideClick: false
        });


        await fetch("control/logout.php", {
            method: "POST",
            credentials: "include"
        });


        localStorage.clear();
        window.location.href = "index.html";
    }


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





