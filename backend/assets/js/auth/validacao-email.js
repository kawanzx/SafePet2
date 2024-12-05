document.addEventListener("DOMContentLoaded", function () {
    //Validar telefone
    document.querySelector("#form-validacao").addEventListener('submit', function (event) {
        event.preventDefault();

        var tipoUsuarioElement = document.getElementById("tipo_usuario");
        var tipo_usuario = tipoUsuarioElement.textContent.trim();
        console.log(tipo_usuario);

        fetch('/backend/auth/confirmar-email.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    Swal.fire({
                        icon: "success",
                        title: "Sucesso!",
                        text: data.mensagem,
                    }).then(() => {
                        window.location.href = tipo_usuario === 'tutor' ? '/backend/views/tutor/buscar.php' : '/backend/views/shared/agendamentos.php';
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erro!",
                        text: data.mensagem,
                    });
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: "Ocorreu um problema ao validar o e-mail.",
                });
            });
    });

    document.getElementById('btn-reenviar-confirmacao').addEventListener('click', function () {
        fetch('/backend/auth/reenviar-email.php')
            .then(response => response.json())
            .then(data => {
                const mensagem = document.getElementById('mensagem-reenvio');
                if (data.sucesso) {
                    mensagem.style.color = 'green';
                    mensagem.innerText = 'E-mail de confirmação reenviado com sucesso!';
                } else {
                    mensagem.style.color = 'red';
                    mensagem.innerText = 'Erro ao reenviar e-mail de confirmação. Tente novamente.';
                }
                mensagem.style.display = 'block';
            })
            .catch(error => {
                console.error('Erro ao reenviar o e-mail de confirmação:', error);
                Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: "Ocorreu um erro. Tente novamente mais tarde.",
                });
            });
    });

    document.getElementById('btn-trocar-email').addEventListener('click', function () {
        document.getElementById('trocar-email-container').style.display = 'block';
    });

    document.getElementById('confirmar-troca').addEventListener('click', function () {
        const novoEmail = document.getElementById('novo-email').value;
        fetch('/backend/auth/trocar-email.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ novo_email: novoEmail })
        })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    Swal.fire({
                        icon: "success",
                        title: "Sucesso!",
                        text: data.mensagem,
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erro!",
                        text: data.mensagem,
                    });
                }
            })
            .catch(error => {
                console.error('Erro ao trocar o telefone:', error);
                Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: "Erro ao trocar o número. Tente novamente.",
                });
            });
    });

});