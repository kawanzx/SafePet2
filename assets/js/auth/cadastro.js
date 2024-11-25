document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("form[id='form-cadastro']").addEventListener('submit', function (event) {
        event.preventDefault();

        var telefoneInput = document.querySelector("input[name='telefone']");
        telefoneInput.addEventListener('input', function () {
            let telefone = telefoneInput.value.replace(/\D/g, '');
            if (telefone.length <= 10) {
                telefone = telefone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3'); // Formato (XX) XXXX-XXXX
            } else {
                telefone = telefone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3'); // Formato (XX) XXXXX-XXXX
            }
            telefoneInput.value = telefone;
        });

        telefoneInput.addEventListener('blur', function () {
            let telefone = telefoneInput.value.replace(/\D/g, '');
            if (telefone.length >= 10) {
                telefone = '+55' + telefone;
            }
        });

        var email = document.querySelector("input[name='email']").value;
        var senha = document.querySelector("input[name='senha']").value;
        var tipo_usuario = document.querySelector("select[name='tipo_usuario']").value;
        var nome_completo = document.querySelector("input[name='nome_completo']").value;
        var telefone = document.querySelector("input[name='telefone']").value;
        var confirmar_senha = document.querySelector("input[name='confirmar_senha']").value;
        var data_nascimento = document.querySelector("input[name='data_nascimento']").value;
        var cpf = document.querySelector("input[name='cpf']").value;
        var genero = document.querySelector("select[name='genero']").value;

        fetch('/auth/register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                email: email,
                senha: senha,
                tipo_usuario: tipo_usuario,
                nome_completo: nome_completo,
                telefone: telefone,
                confirmar_senha: confirmar_senha,
                data_nascimento: data_nascimento,
                cpf: cpf,
                genero: genero
            })
        })
            .then(response => {
                //console.log('Resposta completa:', response);
                if (!response.ok) {
                    throw new Error('Erro na rede: ' + response.statusText);
                }
                return response.text(); 
            })
            .then(text => {
                //console.log('Corpo da resposta:', text);
                try {
                    const data = JSON.parse(text);
                    //console.log('JSON Parseado:', data);
                    Swal.fire({
                        icon: data.sucesso ? "success" : "error",
                        title: data.sucesso ? "Sucesso!" : "Erro!",
                        text: data.mensagem,
                    }).then(() => {
                        if (data.sucesso) {
                            window.location.href = '/auth/validacao-telefone.php';
                        } else{
                            location.reload();
                        }
                    });
                } catch (error) {
                    console.error('Erro ao analisar JSON:', error);
                    Swal.fire({
                        icon: "error",
                        title: "Erro!",
                        text: "Ocorreu um problema ao tentar fazer o cadastro.",
                    });
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: "Ocorreu um problema ao tentar fazer o cadastro.",
                });
            });
    });
});