<?php
include __DIR__ . '/../../auth/protect.php';
?>
<div id="conteudo-4" class="content-section">
    <div class="section">
        <div class="questions-container">
            <h3>Suporte para Cuidadores</h3>
            <div class="question">
                <button>
                    <span>Como cuidar de pets?</span>
                    <i class="fas fa-chevron-down d-arrow"></i>
                </button>
                <p>Para cuidar de pets, é importante entender suas necessidades específicas, como alimentação adequada, atividades físicas, e cuidados médicos regulares. Também é necessário observar o comportamento para identificar qualquer sinal de desconforto ou doença.</p>
            </div>

            <div class="question">
                <button>
                    <span>Quais são as políticas do SafePet?</span>
                    <i class="fas fa-chevron-down d-arrow"></i>
                </button>
                <p>O SafePet exige que os cuidadores sigam regras de ética e responsabilidade, garantindo a segurança e bem-estar dos pets. Além disso, é importante respeitar os horários e acordos de agendamento.</p>
            </div>

            <div class="question">
                <button>
                    <span>Como gerenciar meus agendamentos?</span>
                    <i class="fas fa-chevron-down d-arrow"></i>
                </button>
                <p>Acesse a aba de agendamentos para verificar compromissos futuros, ou confirmar/cancelar serviços diretamente pela plataforma.</p>
            </div>

            <div class="question">
                <button>
                    <span>Como funciona o pagamento?</span>
                    <i class="fas fa-chevron-down d-arrow"></i>
                </button>
                <p>O pagamento é realizado através da plataforma SafePet após a conclusão do serviço. Você pode acompanhar os valores recebidos na aba de "Ganhos" do seu perfil.</p>
            </div>

            <div class="question">
                <button>
                    <span>Como entrar em contato com o suporte?</span>
                    <i class="fas fa-chevron-down d-arrow"></i>
                </button>
                <p>Para suporte, utilize a seção de contato no seu perfil ou envie uma mensagem diretamente via e-mail para a equipe do SafePet em <a href="mailto:suporte.safepet@gmail.com">suporte.safepet@gmail.com</a>, que responderá em até 48 horas.</p>
            </div>
        </div>
        <section class="contato">
            <h3>Não achou sua dúvida? Escreva abaixo</h3>
            <form action="https://api.staticforms.xyz/submit" method="post">
                <form id="contactForm" action="https://api.staticforms.xyz/submit" method="post">
                    <label>Nome</label>
                    <input type="text" name="name" placeholder="Digite seu nome" autocomplete="off" required>
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Digite seu email" autocomplete="off" required>
                    <label>Mensagem</label>
                    <textarea name="message" cols="30" rows="10" placeholder="Digite sua mensagem" required></textarea>
                    <button type="submit">Enviar</button>

                    <input type="hidden" name="accessKey" value="69faecc1-fb39-4467-a250-5ec40ff0baaa">
                    <input type="hidden" name="redirectTo" value="http://localhost:8000/views/cuidador/perfil.php#">
                </form>
        </section>
    </div>
</div>