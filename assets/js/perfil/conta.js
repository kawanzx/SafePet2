//Informações pessoais tutor
document.addEventListener('DOMContentLoaded', () => {
    const editar = document.querySelector('.btn-editar');
    const nomeTutorText = document.querySelector('.nome-tutorText');
    const nomeTutorInput = document.querySelector('.nome-tutorInput');
    const cepText = document.querySelector('.cepText');
    const cepInput = document.querySelector('.cepInput');
    const enderecoText = document.querySelector('.enderecoText');
    const enderecoInput = document.querySelector('.enderecoInput');
    const complementoText = document.querySelector('.complementoText');
    const complementoInput = document.querySelector('.complementoInput');
    const bairroText = document.querySelector('.bairroText');
    const bairroInput = document.querySelector('.bairroInput');
    const cidadeText = document.querySelector('.cidadeText');
    const cidadeInput = document.querySelector('.cidadeInput');
    const ufText = document.querySelector('.ufText');
    const ufInput = document.querySelector('.ufInput');
    const telefoneText = document.querySelector('.telefoneText');
    const telefoneInput = document.querySelector('.telefoneInput');
    const emailText = document.querySelector('.emailText');
    const emailInput = document.querySelector('.emailInput');
    const dt_nascimentoText = document.querySelector('.dt_nascimentoText');
    const dt_nascimentoInput = document.querySelector('.dt_nascimentoInput');


    editar.addEventListener('click', function () {
        if (nomeTutorInput.style.display === 'none') {
            nomeTutorText.style.display = 'none';
            nomeTutorInput.style.display = 'block';
            nomeTutorInput.value = nomeTutorText.textContent.trim();
        } else {
            nomeTutorText.textContent = nomeTutorInput.value.trim();
            nomeTutorText.style.display = 'block';
            nomeTutorInput.style.display = 'none';
        }
        if (cepInput.style.display === 'none') {
            cepText.style.display = 'none';
            cepInput.style.display = 'block';
            cepInput.value = cepText.textContent.trim();
        } else {
            cepText.textContent = cepInput.value.trim();
            cepText.style.display = 'block';
            cepInput.style.display = 'none';
        }
        if (enderecoInput.style.display === 'none') {
            enderecoText.style.display = 'none';
            enderecoInput.style.display = 'block';
            enderecoInput.value = enderecoText.textContent.trim();
        } else {
            enderecoText.textContent = enderecoInput.value.trim();
            enderecoText.style.display = 'block';
            enderecoInput.style.display = 'none';
        }
        if (complementoInput.style.display === 'none') {
            complementoText.style.display = 'none';
            complementoInput.style.display = 'block';
            complementoInput.value = complementoText.textContent.trim();
        } else {
            complementoText.textContent = complementoInput.value.trim();
            complementoText.style.display = 'block';
            complementoInput.style.display = 'none';
        }
        if (bairroInput.style.display === 'none') {
            bairroText.style.display = 'none';
            bairroInput.style.display = 'block';
            bairroInput.value = bairroText.textContent.trim();
        } else {
            bairroText.textContent = bairroInput.value.trim();
            bairroText.style.display = 'block';
            bairroInput.style.display = 'none';
        }
        if (cidadeInput.style.display === 'none') {
            cidadeText.style.display = 'none';
            cidadeInput.style.display = 'block';
            cidadeInput.value = cidadeText.textContent.trim();
        } else {
            cidadeText.textContent = cidadeInput.value.trim();
            cidadeText.style.display = 'block';
            cidadeInput.style.display = 'none';
        }
        if (ufInput.style.display === 'none') {
            ufText.style.display = 'none';
            ufInput.style.display = 'block';
            ufInput.value = ufText.textContent.trim();
        } else {
            ufText.textContent = ufInput.value.trim();
            ufText.style.display = 'block';
            ufInput.style.display = 'none';
        }
        if (telefoneInput.style.display === 'none') {
            telefoneText.style.display = 'none';
            telefoneInput.style.display = 'block';
            telefoneInput.value = telefoneText.textContent.trim();
        } else {
            telefoneText.textContent = telefoneInput.value.trim();
            telefoneText.style.display = 'block';
            telefoneInput.style.display = 'none';
        }
        if (emailInput.style.display === 'none') {
            emailText.style.display = 'none';
            emailInput.style.display = 'block';
            emailInput.value = emailText.textContent.trim();
        } else {
            emailText.textContent = emailInput.value.trim();
            emailText.style.display = 'block';
            emailInput.style.display = 'none';
        }
        if (dt_nascimentoInput.style.display === 'none') {
            dt_nascimentoText.style.display = 'none';
            dt_nascimentoInput.style.display = 'block';
            dt_nascimentoInput.value = dt_nascimentoText.textContent.trim();
        } else {
            dt_nascimentoText.textContent = dt_nascimentoInput.value.trim();
            dt_nascimentoText.style.display = 'block';
            dt_nascimentoInput.style.display = 'none';
        }
    });

    document.querySelector('.cepInput').addEventListener('blur', function () {
        const cep = this.value.trim();

        // Verifica se o CEP tem o formato correto (somente números e 8 dígitos)
        const cepPattern = /^[0-9]{8}$/;
        if (cepPattern.test(cep)) {
            // Faz a requisição para a API ViaCEP
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        // Preencher automaticamente os campos de endereço, bairro, cidade e UF
                        document.querySelector('.enderecoInput').value = data.logradouro;
                        document.querySelector('.bairroInput').value = data.bairro;
                        document.querySelector('.cidadeInput').value = data.localidade;
                        document.querySelector('.ufInput').value = data.uf;
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Erro!",
                            text:'CEP não encontrado!'
                        });
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar o CEP:', error);
                    Swal.fire({
                        icon: "error",
                        title: "Erro!",
                        text:'Falha ao buscar o CEP. Verifique sua conexão e tente novamente.'
                    });
                });
        } else {
            Swal.fire({
                icon: "error",
                title: "Erro!",
                text:'CEP inválido. Use apenas 8 dígitos numéricos.'
            });
        }
    });

    document.querySelectorAll('.btn-salvar').forEach(button => {
        button.addEventListener('click', function () {
            const tutorDiv = this.closest('.section');

            // Obter os valores dos inputs
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

            let valid = true;

            // Limpar mensagens de erro anteriores
            tutorDiv.querySelectorAll('.erro').forEach(span => span.textContent = '');

            // Validação do nome
            if (nome === '' || nome.length < 2) {
                const nomeErro = tutorDiv.querySelector('#nomeErro');
                nomeErro.textContent = 'Nome deve ter pelo menos 2 caracteres.';
                nomeErro.style.color = 'red';
                valid = false;
            }

            // Validação do endereço
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
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(email)) {
                const emailErro = tutorDiv.querySelector('#emailErro');
                emailErro.textContent = 'E-mail inválido.';
                emailErro.style.color = 'red';
                valid = false;
            }

            // Validação da data de nascimento (certifique-se que o campo não está vazio)
            if (dt_nascimento === '') {
                const dtNascimentoErro = tutorDiv.querySelector('#dtNascimentoErro');
                dtNascimentoErro.textContent = 'Data de nascimento é obrigatória.';
                dtNascimentoErro.style.color = 'red';
                valid = false;
            }

            if (valid) {
                const tutorId = tutorDiv.getAttribute('tutor-id');
                // Enviar os dados via AJAX
                const formData = new FormData();
                formData.append('tutorId', tutorId);
                formData.append('nome-tutor', nome);
                formData.append('cep', cep);
                formData.append('endereco', endereco);
                formData.append('complemento', complemento);
                formData.append('bairro', bairro);
                formData.append('cidade', cidade);
                formData.append('uf', uf);
                formData.append('telefone', telefone);
                formData.append('email', email);
                formData.append('dt_nascimento', dt_nascimento);

                fetch('/includes/perfil/info-pessoais.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.text())
                    .then(data => {
                        console.log('Resposta do servidor:', data);
                        if (data.includes("sucesso")) {
                            Swal.fire({
                                icon: "success",
                                text: "Dados atualizados com sucesso!",
                            });
                            // Atualizar os textos com os novos valores
                            tutorDiv.querySelector('.nome-tutorText').textContent = nome;
                            tutorDiv.querySelector('.cepText').textContent = cep;
                            tutorDiv.querySelector('.enderecoText').textContent = endereco;
                            tutorDiv.querySelector('.complementoText').textContent = complemento;
                            tutorDiv.querySelector('.bairroText').textContent = bairro;
                            tutorDiv.querySelector('.cidadeText').textContent = cidade;
                            tutorDiv.querySelector('.ufText').textContent = uf;
                            tutorDiv.querySelector('.telefoneText').textContent = telefone;
                            tutorDiv.querySelector('.emailText').textContent = email;
                            tutorDiv.querySelector('.dt_nascimentoText').textContent = dt_nascimento;

                            // Ocultar os inputs e exibir os textos
                            tutorDiv.querySelectorAll('.nome-tutorText, .cepText, .enderecoText, .complementoText, .bairroText, .cidadeText, .ufText, .telefoneText, .emailText, .dt_nascimentoText').forEach(text => {
                                text.style.display = 'inline';
                            });
                            tutorDiv.querySelectorAll('.nome-tutorInput, .cepInput, .enderecoInput, .complementoInput, .bairroInput, .cidadeInput, .ufInput, .telefoneInput, .emailInput, .dt_nascimentoInput').forEach(input => {
                                input.style.display = 'none';
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Erro ao atualizar os dados!",
                                text: data,
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

        fetch('/includes/perfil/trocar-senha.php', {
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
});

