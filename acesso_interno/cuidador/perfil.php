<?php

include('../../login/protect.php');
include 'navbar.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Completo do Cuidador</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../assets/favicon.ico" alt="">
                <h2>SafePet</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="#" onclick="showContent('conteudo-1', this)"><span class="material-symbols-outlined">account_circle</span>Meu Perfil</a></li>
                    <li><a href="#" onclick="showContent('conteudo-2', this)"><span class="material-symbols-outlined">finance</span>Ganhos</a></li>
                    <li><a href="#" onclick="showContent('conteudo-3', this)"><span class="material-symbols-outlined">person</span>Informações Pessoais</a></li>
                    <li><a href="#" onclick="showContent('conteudo-4', this)"><span class="material-symbols-outlined">info</span>Suporte</a></li>
                    <li><a href="#" onclick="showContent('conteudo-5', this)"><span class="material-symbols-outlined">lock</span>Política de Privacidade</a></li>
                </ul>
            </nav>

        </aside>
        <main class="conteudo">
            <div id="conteudo-1" class="content-section active">
                <h1>Perfil do Cuidador</h1>
                <!-- Seção Completa do Perfil -->
                <div class="perfil-completo">
                    <div class="perfil-header">
                        <img src="https://cdn-icons-png.flaticon.com/512/9706/9706583.png" alt="Foto do Cuidador" class="cuidador-avatar">
                        <div>
                            <h2>Nome do Cuidador</h2>
                            <p><span class="info-label">Avaliação:</span> ⭐⭐⭐⭐ (4.8)</p>
                        </div>
                    </div>
                    <div class="section">
                        <h3>Bio</h3>
                        <p>Apaixonado por animais, cuidando de pets há 5 anos. Sempre tive uma ligação forte com animais e dedico meu tempo a proporcionar o melhor cuidado possível aos pets.</p>
                    </div>
                    <div class="section">
                        <h3>Experiência</h3>
                        <p>Especialista em raças de pequeno porte e animais idosos. Tenho experiência com passeios, alimentação e cuidados gerais, além de lidar com pets que possuem necessidades especiais.</p>
                    </div>
                    <div class="section">
                        <h3>Disponibilidade</h3>
                        <p>Segunda a Sexta: 08:00 - 18:00</p>
                        <p>Sábados: 10:00 - 14:00</p>
                    </div>
                </div>
            </div>
            <div id="conteudo-2" class="content-section">
                <p>conteudo 2</p>
            </div>
            <div id="conteudo-3" class="content-section">
                <p>conteudo 3</p>
            </div>
            <div id="conteudo-4" class="content-section">
             <div class="suporte">
                <h2>Suporte para Cuidadores</h2>

                <h4 class="faq-question">Como cuidar de pets?</h4>
                <p class="faq-answer">Dicas sobre cuidados e bem-estar dos pets.</p>

                <h4 class="faq-question">Quais são as políticas do SafePet?</h4>
                <p class="faq-answer">Informações sobre as diretrizes para cuidadores.</p>

                <h4 class="faq-question">Como gerenciar meus agendamentos?</h4>
                <p class="faq-answer">Orientações para agendamentos e confirmações.</p>

                <h4 class="faq-question">Como funciona o pagamento?</h4>
                <p class="faq-answer">Detalhes sobre pagamentos e comissões.</p>

                <h4 class="faq-question">Como entrar em contato com o suporte?</h4>
                <p class="faq-answer">Informações para entrar em contato com a equipe de suporte.</p>
            </div>

            </div>
                
            </div>
            <div id="conteudo-5" class="content-section">
                <div class="politica-privacidade">
                    <h2>Política de Privacidade</h2>
                    <p>Na SafePet, respeitamos sua privacidade e estamos comprometidos em proteger suas informações pessoais. Esta política explica como coletamos, usamos e protegemos seus dados.</p>
                    
                    <h4>1. Coleta de Informações</h4>
                    <p>Coletamos informações pessoais que você nos fornece ao se cadastrar, como nome, e-mail e telefone.</p>
                    
                    <h4>2. Uso das Informações</h4>
                    <p>Utilizamos suas informações para melhorar nossos serviços, enviar notificações e atender às suas solicitações.</p>
                    
                    <h4>3. Proteção de Dados</h4>
                    <p>Adotamos medidas de segurança para proteger suas informações contra acesso não autorizado.</p>
                    
                    <h4>4. Compartilhamento de Informações</h4>
                    <p>Não compartilhamos suas informações pessoais com terceiros sem seu consentimento.</p>
                    
                    <h4>5. Alterações na Política</h4>
                    <p>Podemos atualizar esta política periodicamente. Avisaremos sobre mudanças significativas.</p>
                    
                    <p>Se você tiver dúvidas sobre nossa política de privacidade, entre em contato conosco.</p>
                </div>
            </div>
        </main>
    </div>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>