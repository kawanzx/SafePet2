//Informações pessoais tutor
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.cepInput').addEventListener('blur', function () {
        const cep = this.value.trim();

        const cepPattern = /^[0-9]{8}$/;
        if (cepPattern.test(cep)) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        document.querySelector('.enderecoInput').value = data.logradouro;
                        document.querySelector('.bairroInput').value = data.bairro;
                        document.querySelector('.cidadeInput').value = data.localidade;
                        document.querySelector('.ufInput').value = data.uf;
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Erro!",
                            text: 'CEP não encontrado!'
                        });
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar o CEP:', error);
                    Swal.fire({
                        icon: "error",
                        title: "Erro!",
                        text: 'Falha ao buscar o CEP. Verifique sua conexão e tente novamente.'
                    });
                });
        } else {
            Swal.fire({
                icon: "error",
                title: "Erro!",
                text: 'CEP inválido. Use apenas 8 dígitos numéricos.'
            });
        }
    });

    const telefoneInput = document.querySelector('.telefoneInput');
    const codigoTelInput = document.querySelector('.codigoTelInput');
    const btnTelefone = document.getElementById('btn-telefone');
    const btnSalvar = document.getElementById('btn-salvar');
    const btnReenviartelefone = document.getElementById('btn-reenviar-tel');
    const timerTelefone = document.getElementById('timer-tel');

    btnTelefone.disabled = true;

    telefoneInput.addEventListener('click', function () {
        const telefone = telefoneInput.value.trim();

        if (telefone !== '') {
            btnTelefone.disabled = false;
            codigoTelInput.required = true;
            codigoTelInput.value = '';
            btnSalvar.disabled = true;
        } else {
            btnTelefone.disabled = true;
            codigoTelInput.required = false;
        }

        codigoTelInput.addEventListener('input', function () {
            if (codigoTelInput.value.trim() !== '' && telefoneInput.value.trim() !== '') {
                btnSalvar.disabled = false;
            } else {
                btnSalvar.disabled = true;
            }
        });

        btnTelefone.addEventListener('click', function (event) {
            event.preventDefault();

            btnTelefone.style.display = 'none';

            btnReenviartelefone.style.display = 'inline-block';
            timerTelefone.style.display = 'inline-block';

            let tempoRestante = 60;

            // Atualiza o contador a cada segundo
            const intervalo = setInterval(function () {
                timerTelefone.textContent = `Aguarde ${tempoRestante} segundos para reenviar o código.`;
                tempoRestante--;

                if (tempoRestante < 0) {
                    clearInterval(intervalo);
                    timerTelefone.textContent = '';
                    btnReenviartelefone.disabled = false;
                }
            }, 1000);

            btnReenviartelefone.disabled = true;

            const telefone = telefoneInput.value.trim();

            if (telefone) {
                fetch('../../../includes/perfil/enviar-codigo.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ telefone: telefone })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.sucesso) {
                            Swal.fire('Sucesso', data.mensagem, 'success');
                            window.telefoneAlterado = true;
                            btnSalvar.disabled = false;
                        } else {
                            Swal.fire('Erro', data.mensagem, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao enviar código:', error);
                        Swal.fire('Erro', 'Falha ao enviar o código.', 'error');
                    });
            } else {
                Swal.fire('Erro', 'Por favor, insira um número de telefone.', 'error');
            }
        });
    });

    btnReenviartelefone.addEventListener('click', function (event) {
        event.preventDefault();

        const telefone = telefoneInput.value.trim();

        if (telefone) {
            fetch('../../../includes/perfil/enviar-codigo.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ telefone: telefone })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        console.log("Código reenviado");
                        Swal.fire('Sucesso', data.mensagem, 'success');
                        window.telefoneAlterado = true;
                        btnSalvar.disabled = false;
                    } else {
                        Swal.fire('Erro', data.mensagem, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro ao enviar código:', error);
                    Swal.fire('Erro', 'Falha ao enviar o código.', 'error');
                });
        } else {
            Swal.fire('Erro', 'Por favor, insira um número de telefone.', 'error');
        }

        let tempoRestante = 90;

        // Atualiza o contador a cada segundo
        const intervalo = setInterval(function () {
            timerTelefone.textContent = `Aguarde ${tempoRestante} segundos para reenviar o código.`;
            tempoRestante--;

            if (tempoRestante < 0) {
                clearInterval(intervalo);
                timerTelefone.textContent = '';
                btnReenviartelefone.disabled = false;
            }
        }, 1000);
    })

    document.querySelectorAll('.btn-salvar').forEach(button => {
        button.addEventListener('click', function () {
            const tutorDiv = this.closest('.section');

            const nome = tutorDiv.querySelector('.nome-tutorInput').value.trim();
            const cep = tutorDiv.querySelector('.cepInput').value.trim();
            const endereco = tutorDiv.querySelector('.enderecoInput').value.trim();
            const complemento = tutorDiv.querySelector('.complementoInput').value.trim();
            const bairro = tutorDiv.querySelector('.bairroInput').value.trim();
            const cidade = tutorDiv.querySelector('.cidadeInput').value.trim();
            const uf = tutorDiv.querySelector('.ufInput').value.trim();
            const telefone = tutorDiv.querySelector('.telefoneInput').value.trim();
            const email = tutorDiv.querySelector('.emailInput').value.trim();
            const dt_nascimento = tutorDiv.querySelector('.dt_nascimentoInput').value.trim();
            const codigo = tutorDiv.querySelector('.codigoTelInput').value.trim();

            let valid = true;

            // Limpar mensagens de erro anteriores
            tutorDiv.querySelectorAll('.erro').forEach(span => span.textContent = '');

            if (window.telefoneAlterado && codigo === '') {
                const codigoErro = tutorDiv.querySelector('#codigoTelErro');
                codigoErro.textContent = 'Por favor, insira o código de verificação.';
                codigoErro.style.color = 'red';
                valid = false;
            }

            // Validação do nome
            if (nome === '' || nome.length < 2) {
                const nomeErro = tutorDiv.querySelector('#nomeErro');
                nomeErro.textContent = 'Nome deve ter pelo menos 2 caracteres.';
                nomeErro.style.color = 'red';
                valid = false;
            }

            if (cep === '') {
                const cepErro = tutorDiv.querySelector('#cepErro');
                cepErro.textContent = 'CEP é obrigatório.';
                cepErro.style.color = 'red';
                valid = false;
            }

            if (endereco === '') {
                const enderecoErro = tutorDiv.querySelector('#enderecoErro');
                enderecoErro.textContent = 'Endereço é obrigatório.';
                enderecoErro.style.color = 'red';
                valid = false;
            }

            // Validação do complemento (não obrigatório, mas deve ser válido caso informado)
            if (complemento.length > 100) {
                const complementoErro = tutorDiv.querySelector('#complementoErro');
                complementoErro.textContent = 'Complemento muito longo.';
                complementoErro.style.color = 'red';
                valid = false;
            }

            // Validação do bairro
            if (bairro === '') {
                const bairroErro = tutorDiv.querySelector('#bairroErro');
                bairroErro.textContent = 'Bairro é obrigatório.';
                bairroErro.style.color = 'red';
                valid = false;
            }

            // Validação da cidade
            if (cidade === '') {
                const cidadeErro = tutorDiv.querySelector('#cidadeErro');
                cidadeErro.textContent = 'Cidade é obrigatória.';
                cidadeErro.style.color = 'red';
                valid = false;
            }

            // Validação do UF (deve ter 2 caracteres)
            const ufPattern = /^[A-Z]{2}$/;
            if (!ufPattern.test(uf)) {
                const ufErro = tutorDiv.querySelector('#ufErro');
                ufErro.textContent = 'UF inválido. Use 2 letras maiúsculas.';
                ufErro.style.color = 'red';
                valid = false;
            }

            // Validação do telefone (10 ou 11 dígitos, sem caracteres especiais)
            const telefonePattern = /^[0-9]{10,11}$/;
            if (!telefonePattern.test(telefone)) {
                const telefoneErro = tutorDiv.querySelector('#telefoneErro');
                telefoneErro.textContent = 'Telefone inválido. Use 10 ou 11 dígitos numéricos.';
                telefoneErro.style.color = 'red';
                valid = false;
            }

            // Validação do e-mail
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(email)) {
                const emailErro = tutorDiv.querySelector('#emailErro');
                emailErro.textContent = 'E-mail inválido.';
                emailErro.style.color = 'red';
                valid = false;
            }

            // Validação da data de nascimento
            if (dt_nascimento === '') {
                const dtNascimentoErro = tutorDiv.querySelector('#dtNascimentoErro');
                dtNascimentoErro.textContent = 'Data de nascimento é obrigatória.';
                dtNascimentoErro.style.color = 'red';
                valid = false;
            }

            if (valid) {
                const tutorId = tutorDiv.getAttribute('tutor-id');
                const formData = new FormData();
                formData.append('tutorId', tutorId);
                formData.append('nome', nome);
                formData.append('cep', cep);
                formData.append('endereco', endereco);
                formData.append('complemento', complemento);
                formData.append('bairro', bairro);
                formData.append('cidade', cidade);
                formData.append('uf', uf);
                formData.append('telefone', telefone);
                formData.append('email', email);
                formData.append('dt_nascimento', dt_nascimento);
                formData.append('codigo', codigo);

                //console.log('Nome enviado:', formData.get('nome'));

                fetch('/backend/includes/perfil/info-pessoais.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.text())  // Retorne como texto primeiro para depuração
                    .then(text => {
                        console.log('Resposta bruta do servidor:', text);  // Verifique a resposta bruta

                        // Se a resposta não estiver vazia, tente convertê-la em JSON
                        try {
                            const data = JSON.parse(text);
                            console.log('Resposta do servidor (JSON):', data);

                            // Verifique o sucesso da resposta e mostre a mensagem
                            if (data.sucesso) {
                                Swal.fire('Sucesso', data.mensagem, 'success');
                            } else {
                                Swal.fire('Erro', data.mensagem, 'error');
                            }
                        } catch (error) {
                            console.error('Erro ao processar a resposta JSON:', error);
                            Swal.fire({
                                icon: "error",
                                title: "Erro ao processar a resposta do servidor.",
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Erro na requisição:', error);
                        Swal.fire({
                            icon: "error",
                            title: "Erro na comunicação com o servidor.",
                        });
                    });
            }
        });
    });

    // Trocar senha

    window.validarSenha = function () {
        var senhaAntiga = document.getElementById("senha_antiga").value;
        var novaSenha = document.getElementById("nova_senha").value;
        var confirmarSenha = document.getElementById("confirmar_senha").value;

        var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{6,}$/;

        if (!regex.test(novaSenha)) {
            Swal.fire({
                icon: "error",
                title: "Erro!",
                text: "A senha deve conter pelo menos 6 caracteres, incluindo letras maiúsculas, minúsculas, números e caracteres especiais.",
            });
            return false;
        }

        if (novaSenha !== confirmarSenha) {
            Swal.fire({
                icon: "error",
                title: "As senhas não coincidem.",
            });
            return false;
        }

        fetch('/backend/includes/perfil/trocar-senha.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                senha_antiga: senhaAntiga,
                nova_senha: novaSenha,
                confirmar_senha: confirmarSenha
            })
        })
            .then(response => {
                // Verifique a resposta e leia o corpo uma única vez
                if (!response.ok) {
                    throw new Error('Erro na rede: ' + response.statusText);
                }
                return response.json(); // Leia o corpo da resposta
            })
            .then(data => {
                Swal.fire({
                    icon: data.sucesso ? "success" : "error",
                    title: data.sucesso ? "Sucesso!" : "Erro!",
                    text: data.mensagem,
                });
                return data.sucesso;
            })
            .catch(error => {
                console.error('Erro:', error);
                Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: "Ocorreu um problema ao tentar alterar a senha.",
                });
            });
    };

    window.confirmExclusao = function (event) {
        event.preventDefault();

        Swal.fire({
            title: "Você tem certeza que deseja excluir sua conta?",
            text: "Não será possível reverter essa ação!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, excluir conta",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('formExcluirConta');
                if (form) {
                    form.submit();
                }
            }
        });
    }

});

// Verifica se tem um endereço cadastrado

document.addEventListener('DOMContentLoaded', function () {

    const cepInput = document.querySelector('.cepInput');
    const cep = cepInput ? cepInput.value.trim() : '';

    if (cep === '' || cep === null) {
        document.querySelector('.endereco').style.display = 'none';
        document.getElementById('mensagemEndereco').style.display = 'block';
    }

    // Ao clicar em "Cadastrar", exibe o formulário de cadastro de endereço
    document.getElementById('cadastrarEnderecoBtn').addEventListener('click', function (event) {
        event.preventDefault();
        document.querySelector('.endereco').style.display = 'block';

        const inputs = document.querySelectorAll('.nome-tutorInput, .cepInput, .enderecoInput, .complementoInput, .bairroInput, .cidadeInput, .ufInput, .telefoneInput, .emailInput, .dt_nascimentoInput');
        inputs.forEach(input => {
            input.style.display = 'block'; // Exibe o input
        });

        const textos = document.querySelectorAll('.nome-tutorText, .cepText, .enderecoText, .complementoText, .bairroText, .cidadeText, .ufText, .telefoneText, .emailText, .dt_nascimentoText');
        textos.forEach(text => {
            text.style.display = 'none'; // Oculta o texto
        });
        document.getElementById('mensagemEndereco').style.display = 'none';
    });

    const inputsTexto = document.querySelectorAll('.textfield > input[type="text"]');

    inputsTexto.forEach(input => {
        // Define o tamanho inicial
        adjustInputWidth(input);

        // Ajusta o tamanho à medida que o usuário digita
        input.addEventListener('input', function () {
            adjustInputWidth(input);
        });
    });

    // Função que ajusta a largura do input com base no comprimento do texto
    function adjustInputWidth(input) {
        input.style.width = (input.value.length + 1) + 'ch'; // A largura é dada pelo número de caracteres
    }
});

