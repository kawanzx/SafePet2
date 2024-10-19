
/* NavBar confirmação de logout*/
document.getElementById('logoutLink').addEventListener('click', function (event) {
    event.preventDefault();

    var confirmation = window.confirm("Você realmente deseja sair?");
    if (confirmation) {
        window.location.href = "/login/logout.php";
    }
});


/* Sidebar */
// Função para exibir o conteúdo com base no ID da seção
function showContent(sectionId, element) {
    // Oculta todas as seções de conteúdo
    var sections = document.querySelectorAll('.content-section');
    sections.forEach(function (section) {
        section.classList.remove('active');
    });

    // Exibe a seção selecionada
    var activeSection = document.getElementById(sectionId);
    activeSection.classList.add('active');

    // Exibe a seção selecionada
    var activeSection = document.getElementById(sectionId);
    activeSection.classList.add('active');

    // Remove a classe 'active' de todos os links da sidebar
    var links = document.querySelectorAll('.sidebar a');
    links.forEach(function (link) {
        link.classList.remove('active');
    });

    // Adiciona a classe 'active' ao link clicado
    element.classList.add('active');
}

// Apagar campos após cadastrar pet

document.getElementById('form-pet').addEventListener('submit', function (event) {
    // Previne o comportamento padrão do envio do formulário
    event.preventDefault();

    // Obtenha os dados do formulário
    var formData = new FormData(this);

    // Envia o formulário via AJAX (fetch)
    fetch('cadastro-pet.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message); // Exibe a mensagem de sucesso
                document.getElementById('form-pet').reset(); // Limpar o formulário
            } else {
                alert('Erro: ' + data.message); // Exibe a mensagem de erro
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Ocorreu um erro ao tentar cadastrar o pet.');
        });
});



// Editar Pet

document.querySelectorAll('.editar-btn').forEach(button => {
    button.addEventListener('click', function () {
        const petDiv = this.closest('.pet');

        // Armazenar os valores atuais dos textos em atributos data nos inputs
        petDiv.querySelector('.nomePetInput').setAttribute('data-original', petDiv.querySelector('.nomePetInput').value);
        petDiv.querySelector('.especieInput').setAttribute('data-original', petDiv.querySelector('.especieInput').value);
        petDiv.querySelector('.racaInput').setAttribute('data-original', petDiv.querySelector('.racaInput').value);
        petDiv.querySelector('.idadeInput').setAttribute('data-original', petDiv.querySelector('.idadeInput').value);
        petDiv.querySelector('.sexoInput').setAttribute('data-original', petDiv.querySelector('.sexoInput').value);
        petDiv.querySelector('.pesoInput').setAttribute('data-original', petDiv.querySelector('.pesoInput').value);
        petDiv.querySelector('.castradoInput').setAttribute('data-original', petDiv.querySelector('.castradoInput').value);
        petDiv.querySelector('.descricaoInput').setAttribute('data-original', petDiv.querySelector('.descricaoInput').value);

        // Ocultar os textos e exibir os inputs
        petDiv.querySelectorAll('.nomePetText, .especieText, .racaText, .idadeText, .sexoText, .pesoText, .castradoText, .descricaoText').forEach(text => {
            text.style.display = 'none';
        });
        petDiv.querySelectorAll('.nomePetInput, .especieInput, .racaInput, .idadeInput, .sexoInput, .pesoInput, .castradoInput, .descricaoInput').forEach(input => {
            input.style.display = 'block';
        });

        // Alternar os botões
        this.style.display = 'none';
        petDiv.querySelector('.salvar-btn').style.display = 'inline-block';
        petDiv.querySelector('.cancelar-btn').style.display = 'inline-block';
    });
});

document.querySelectorAll('.salvar-btn').forEach(button => {
    button.addEventListener('click', function () {
        const petDiv = this.closest('.pet');
        const petId = petDiv.getAttribute('data-pet-id'); // Supondo que você tenha um atributo data-id com o ID do pet

        // Obter os valores dos inputs
        const nome = petDiv.querySelector('.nomePetInput').value;
        const especie = petDiv.querySelector('.especieInput').value;
        const raca = petDiv.querySelector('.racaInput').value;
        const idade = petDiv.querySelector('.idadeInput').value;
        const sexo = petDiv.querySelector('.sexoInput').value;
        const peso = petDiv.querySelector('.pesoInput').value;
        const castrado = petDiv.querySelector('.castradoInput').value;
        const descricao = petDiv.querySelector('.descricaoInput').value;
        const fotoInput = petDiv.querySelector('input[type="file"]');
        const fotoAtual = petDiv.querySelector('.fotoPetAtual').value;

        // Enviar os dados via AJAX
        const formData = new FormData();
        formData.append('petId', petId);
        formData.append('nome', nome);
        formData.append('especie', especie);
        formData.append('raca', raca);
        formData.append('idade', idade);
        formData.append('sexo', sexo);
        formData.append('peso', peso);
        formData.append('castrado', castrado);
        formData.append('descricao', descricao);

        if (fotoInput && fotoInput.files.length > 0) {
            formData.append('foto', fotoInput.files[0]);
        } else {
            // Se não houver uma nova foto, adicione o valor da foto atual (se existir)
            formData.append('foto_atual', fotoAtual);
        }

        fetch('editar-pet.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                if (data.includes("sucesso")) {
                    // Atualizar os textos com os novos valores
                    petDiv.querySelector('.nomePetText').textContent = nome;
                    petDiv.querySelector('.especieText').textContent = especie === 'cachorro' ? 'Cachorro' : 'Gato';
                    petDiv.querySelector('.racaText').textContent = raca;
                    petDiv.querySelector('.idadeText').textContent = idade;
                    petDiv.querySelector('.sexoText').textContent = sexo;
                    petDiv.querySelector('.pesoText').textContent = peso;
                    petDiv.querySelector('.castradoText').textContent = castrado;
                    petDiv.querySelector('.descricaoText').textContent = descricao;

                    // Atualizar a imagem caso uma nova foto tenha sido enviada
                    if (fotoInput && fotoInput.files.length > 0) {
                        const newImageUrl = URL.createObjectURL(fotoInput.files[0]);
                        petDiv.querySelector('.fotoPet img').src = newImageUrl;
                    }

                    // Ocultar os inputs e exibir os textos
                    petDiv.querySelectorAll('.nomePetText, .especieText, .racaText, .idadeText, .sexoText, .pesoText, .castradoText, .descricaoText').forEach(text => {
                        text.style.display = 'inline';
                    });
                    petDiv.querySelectorAll('.nomePetInput, .especieInput, .racaInput, .idadeInput, .sexoInput, .pesoInput, .castradoInput, .descricaoInput, .fotoPetInput').forEach(input => {
                        input.style.display = 'none';
                    });

                    // Alternar os botões
                    petDiv.querySelector('.salvar-btn').style.display = 'none';
                    petDiv.querySelector('.editar-btn').style.display = 'inline-block';
                } else {
                    alert('Erro ao atualizar os dados: ' + data);
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                alert('Erro na comunicação com o servidor.');
            });
    });
});


document.querySelectorAll('.cancelar-btn').forEach(button => {
    button.addEventListener('click', function () {
        const petDiv = this.closest('.pet');

        // Restaurar os valores originais dos inputs
        petDiv.querySelectorAll('.nomePetInput, .especieInput, .racaInput, .idadeInput, .sexoInput, .pesoInput, .castradoInput, .descricaoInput').forEach(input => {
            input.value = input.getAttribute('data-original');
        });

        // Ocultar os inputs e exibir os textos
        petDiv.querySelectorAll('.nomePetText, .especieText, .racaText, .idadeText, .sexoText, .pesoText, .castradoText, .descricaoText').forEach(text => {
            text.style.display = 'inline';
        });
        petDiv.querySelectorAll('.nomePetInput, .especieInput, .racaInput, .idadeInput, .sexoInput, .pesoInput, .castradoInput, .descricaoInput, .fotoPetInput').forEach(input => {

            input.style.display = 'none';
        });

        // Alternar os botões
        petDiv.querySelector('.salvar-btn').style.display = 'none';
        petDiv.querySelector('.cancelar-btn').style.display = 'none';
        petDiv.querySelector('.editar-btn').style.display = 'inline-block';
    });
});


document.querySelectorAll('.fotoPetImg').forEach(image => {
    image.addEventListener('click', function () {
        const petDiv = this.closest('.pet');
        const fileInput = petDiv.querySelector('.fotoPetInput');
        fileInput.click();
    });
});


//Excluir Pet
// Evento para excluir um pet
document.querySelectorAll('.excluir-btn').forEach(button => {
    button.addEventListener('click', function () {
        if (confirm('Tem certeza que deseja excluir este pet?')) {
            const petElement = this.closest('.pet');
            const petId = petElement.getAttribute('data-pet-id');

            // Enviar requisição AJAX para excluir o pet
            fetch('excluir-pet.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'petId=' + encodeURIComponent(petId)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        petElement.remove(); // Remove o elemento da página
                    } else {
                        alert('Erro: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Ocorreu um erro ao tentar excluir o pet.');
                });
        }
    });
});

// Foto preview cadastro pet
document.getElementById('foto').addEventListener('change', function (event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0]; // O arquivo selecionado pelo usuário

    if (file) {
        const reader = new FileReader(); // Cria um FileReader para ler o arquivo
        reader.onload = function (e) {
            preview.src = e.target.result; // Define a fonte da imagem para o conteúdo do arquivo
            preview.style.display = 'block'; // Exibe a imagem
        };
        reader.readAsDataURL(file); // Lê o arquivo como uma URL de dados (base64)
    } else {
        preview.src = ''; // Limpa a imagem se nenhum arquivo for selecionado
        preview.style.display = 'none'; // Oculta a imagem
    }
});


// Foto preview editar pet
document.querySelectorAll('.fotoPetInput').forEach(function (input) {
    input.addEventListener('change', function (event) {
        const petId = event.target.id.split('-')[1]; // Extrai o ID do pet do input file
        const preview = document.getElementById('preview-' + petId); // Obtém o elemento de pré-visualização da imagem
        const file = event.target.files[0]; // O arquivo selecionado

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result; // Define o src da imagem para o conteúdo do arquivo
            };
            reader.readAsDataURL(file); // Lê o arquivo como uma URL base64

            // Criar um objeto FormData para enviar o arquivo via AJAX
            let formData = new FormData();
            formData.append('foto', file); // Anexa o arquivo de imagem
            formData.append('pet_id', petId); // Anexa o ID do pet

            // Enviar a imagem para o servidor via AJAX
            fetch('atualizar_foto.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text()) // Pega a resposta do servidor
                .then(data => {
                    console.log(data); // Exibe a resposta (se desejar)
                })
                .catch(error => {
                    console.error('Erro ao atualizar a foto:', error);
                    alert('Houve um erro ao atualizar a foto.');
                });
        } else {
            preview.src = 'default-image.png'; // Imagem padrão se nenhum arquivo for selecionado
        }
    });
});

//Informações pessoais tutor

document.querySelectorAll('.btn-editar-nome').forEach(button => {
    button.addEventListener('click', function () {
        const tutorDiv = this.closest('.informacoes-pessoais');

        // Armazenar os valores atuais dos textos em atributos data nos inputs
        tutorDiv.querySelector('.nome-tutorInput').setAttribute('data-original', tutorDiv.querySelector('.nome-tutorInput').value);


        // Ocultar os textos e exibir os inputs
        tutorDiv.querySelectorAll('.nome-tutorText').forEach(text => {
            text.style.display = 'none';
        });
        tutorDiv.querySelectorAll('.nome-tutorInput').forEach(input => {
            input.style.display = 'block';
        });
    });
});

document.querySelectorAll('.btn-editar-endereco').forEach(button => {
    button.addEventListener('click', function () {
        const tutorDiv = this.closest('.informacoes-pessoais');

        // Armazenar os valores atuais dos textos em atributos data nos inputs
        tutorDiv.querySelector('.enderecoInput').setAttribute('data-original', tutorDiv.querySelector('.enderecoInput').value);


        // Ocultar os textos e exibir os inputs
        tutorDiv.querySelectorAll('.enderecoText').forEach(text => {
            text.style.display = 'none';
        });
        tutorDiv.querySelectorAll('.enderecoInput').forEach(input => {
            input.style.display = 'block';
        });
    });
});



document.querySelectorAll('.btn-editar-telefone').forEach(button => {
    button.addEventListener('click', function () {
        const tutorDiv = this.closest('.informacoes-pessoais');

        // Armazenar os valores atuais dos textos em atributos data nos inputs
        tutorDiv.querySelector('.telefoneInput').setAttribute('data-original', tutorDiv.querySelector('.telefoneInput').value);


        // Ocultar os textos e exibir os inputs
        tutorDiv.querySelectorAll('.telefoneText').forEach(text => {
            text.style.display = 'none';
        });
        tutorDiv.querySelectorAll('.telefoneInput').forEach(input => {
            input.style.display = 'block';
        });
    });
});

document.querySelectorAll('.btn-editar-email').forEach(button => {
    button.addEventListener('click', function () {
        const tutorDiv = this.closest('.informacoes-pessoais');

        // Armazenar os valores atuais dos textos em atributos data nos inputs
        tutorDiv.querySelector('.emailInput').setAttribute('data-original', tutorDiv.querySelector('.emailInput').value);


        // Ocultar os textos e exibir os inputs
        tutorDiv.querySelectorAll('.emailText').forEach(text => {
            text.style.display = 'none';
        });
        tutorDiv.querySelectorAll('.emailInput').forEach(input => {
            input.style.display = 'block';
        });
    });
});

document.querySelectorAll('.btn-editar-dt_nascimento').forEach(button => {
    button.addEventListener('click', function () {
        const tutorDiv = this.closest('.informacoes-pessoais');

        // Armazenar os valores atuais dos textos em atributos data nos inputs
        tutorDiv.querySelector('.dt_nascimentoInput').setAttribute('data-original', tutorDiv.querySelector('.dt_nascimentoInput').value);


        // Ocultar os textos e exibir os inputs
        tutorDiv.querySelectorAll('.dt_nascimentoText').forEach(text => {
            text.style.display = 'none';
        });
        tutorDiv.querySelectorAll('.dt_nascimentoInput').forEach(input => {
            input.style.display = 'block';
        });
    });
});

document.querySelectorAll('.btn-salvar').forEach(button => {
    button.addEventListener('click', function () {
        const tutorDiv = this.closest('.informacoes-pessoais');

        // Obter os valores dos inputs
        const nome = tutorDiv.querySelector('.nome-tutorInput').value.trim();
        const endereco = tutorDiv.querySelector('.enderecoInput').value.trim();
        const telefone = tutorDiv.querySelector('.telefoneInput').value.trim();
        const email = tutorDiv.querySelector('.emailInput').value.trim();
        const dt_nascimento = tutorDiv.querySelector('.dt_nascimentoInput').value.trim();

        let valid = true;

        // Limpar mensagens de erro anteriores
        tutorDiv.querySelectorAll('.erro').forEach(span => span.textContent = '');

        // Validação do nome
        if (nome === '' || nome.length < 2) {
            tutorDiv.querySelector('#nomeErro').textContent = 'Nome deve ter pelo menos 2 caracteres.';
            valid = false;
        }

        // Validação do endereço
        if (endereco === '') {
            tutorDiv.querySelector('#enderecoErro').textContent = 'Endereço é obrigatório.';
            valid = false;
        }

        // Validação do telefone (10 ou 11 dígitos, sem caracteres especiais)
        const telefonePattern = /^[0-9]{10,11}$/;
        if (!telefonePattern.test(telefone)) {
            tutorDiv.querySelector('#telefoneErro').textContent = 'Telefone inválido. Use 10 ou 11 dígitos numéricos.';
            valid = false;
        }

        // Validação do e-mail
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email)) {
            tutorDiv.querySelector('#emailErro').textContent = 'E-mail inválido.';
            valid = false;
        }

        // Validação da data de nascimento (certifique-se que o campo não está vazio)
        if (dt_nascimento === '') {
            tutorDiv.querySelector('#dtNascimentoErro').textContent = 'Data de nascimento é obrigatória.';
            valid = false;
        }

        if (valid) {
            const tutorId = tutorDiv.getAttribute('tutor-id');
            // Enviar os dados via AJAX
            const formData = new FormData();
            formData.append('tutorId', tutorId);
            formData.append('nome-tutor', nome);
            formData.append('endereco', endereco);
            formData.append('telefone', telefone);
            formData.append('email', email);
            formData.append('dt_nascimento', dt_nascimento);

            fetch('info-pessoais.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    console.log('Resposta do servidor:', data);
                    if (data.includes("sucesso")) {
                        // Atualizar os textos com os novos valores
                        tutorDiv.querySelector('.nome-tutorText').textContent = nome;
                        tutorDiv.querySelector('.enderecoText').textContent = endereco;
                        tutorDiv.querySelector('.telefoneText').textContent = telefone;
                        tutorDiv.querySelector('.emailText').textContent = email;
                        tutorDiv.querySelector('.dt_nascimentoText').textContent = dt_nascimento;

                        // Ocultar os inputs e exibir os textos
                        tutorDiv.querySelectorAll('.nome-tutorText, .enderecoText, .telefoneText, .emailText, .dt_nascimentoText').forEach(text => {
                            text.style.display = 'inline';
                        });
                        tutorDiv.querySelectorAll('.nome-tutorInput, .enderecoInput, .telefoneInput, .emailInput, .dt_nascimentoInput').forEach(input => {
                            input.style.display = 'none';
                        });
                        alert('Dados atualizados com sucesso!');
                    } else {
                        alert('Erro ao atualizar os dados: ' + data);
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                    alert('Erro na comunicação com o servidor.');
                });
        }
    });
});

// Trocar senha

function validarSenha() {
    var novaSenha = document.getElementById("nova_senha").value;
    var confirmarSenha = document.getElementById("confirmar_senha").value;

    // Expressão regular para verificar a senha
    var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{6,}$/;

    if (!regex.test(novaSenha)) {
        alert("A senha deve conter pelo menos 6 caracteres, incluindo letras maiúsculas, minúsculas, números e caracteres especiais.");
        return false;
    }

    if (novaSenha !== confirmarSenha) {
        alert("As senhas não coincidem.");
        return false;
    }

    return true; 
}

