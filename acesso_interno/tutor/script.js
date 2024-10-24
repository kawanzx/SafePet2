
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
const sidebar = document.querySelector('.sidebar');

// Função para alternar a expansão da sidebar ao clicar no cabeçalho
function toggleSidebar() {
    sidebar.classList.toggle('sidebar-expandida');

    // Se a sidebar for recolhida, esconda todos os submenus
    if (!sidebar.classList.contains('sidebar-expandida')) {
        const submenus = sidebar.querySelectorAll('.submenu');
        submenus.forEach(submenu => {
            submenu.style.display = 'none'; // Esconde todos os submenus
        });
    }
}

// Adicionando o evento de clique ao cabeçalho da sidebar
const header = document.querySelector('.sidebar-header');
header.addEventListener('click', toggleSidebar);

// Adicionando o evento de clique aos ícones na sidebar
const icons = sidebar.querySelectorAll('nav ul li > a > span.material-symbols-outlined');
icons.forEach(icon => {
    icon.parentElement.addEventListener('click', function (event) {
        // Impede que a sidebar seja recolhida ao clicar no ícone
        event.stopPropagation();

        // Expandir a sidebar se estiver recolhida
        if (!sidebar.classList.contains('sidebar-expandida')) {
            sidebar.classList.add('sidebar-expandida');
        }
    });
});

// Função para fechar a sidebar ao clicar fora dela
document.addEventListener('click', function (event) {
    // Verifica se o clique foi fora da sidebar e se a sidebar está expandida
    if (!sidebar.contains(event.target) && sidebar.classList.contains('sidebar-expandida')) {
        sidebar.classList.remove('sidebar-expandida'); // Recolhe a sidebar

        // Esconde todos os submenus quando a sidebar é recolhida
        const submenus = sidebar.querySelectorAll('.submenu');
        submenus.forEach(submenu => {
            submenu.style.display = 'none';
        });
    }
});

// Função para mostrar o conteúdo
function showContent(sectionId, element) {
    // Oculta todas as seções de conteúdo
    var sections = document.querySelectorAll('.content-section');
    sections.forEach(function (section) {
        section.classList.remove('active');
    });

    // Exibe a seção selecionada
    var activeSection = document.getElementById(sectionId);
    if (activeSection) {
        activeSection.classList.add('active');
    }

    // Remove a classe 'active' de todos os links da sidebar
    var links = document.querySelectorAll('.sidebar a');
    links.forEach(function (link) {
        link.classList.remove('active');
    });

    // Adiciona a classe 'active' ao link clicado
    if (element) {
        element.classList.add('active');
    }

    // Se o link clicado é parte do submenu, não recolhe a sidebar
    var isSubmenu = element.closest('ul.submenu');
    if (isSubmenu) {
        var subContentId = element.dataset.subContent;

        // Oculta todos os sub-conteúdos
        var subSections = document.querySelectorAll('#' + sectionId + ' > div');
        subSections.forEach(function (subSection) {
            subSection.style.display = 'none';
        });

        // Exibe o sub-conteúdo selecionado
        if (subContentId) {
            var activeSubSection = document.getElementById(subContentId);
            if (activeSubSection) {
                activeSubSection.style.display = 'block';
            }
        }
        return;
    }

    // Após a seleção do conteúdo, recolhe a sidebar se não for um submenu
    if (sidebar.classList.contains('sidebar-expandida')) {
        sidebar.classList.remove('sidebar-expandida');
    }
}

// Função para alternar a exibição do submenu
function alternarSubmenu(event) {
    event.preventDefault();
    const submenu = event.currentTarget.nextElementSibling;

    if (submenu) {
        if (submenu.style.display === 'block') {
            submenu.style.display = 'none';
        } else {
            submenu.style.display = 'block';
        }
    }
}


// Trocar foto de perfil

function previewAndUploadFoto() {
    var input = document.getElementById('input-foto');
    var preview = document.getElementById('preview-avatar');

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result; // Atualiza o preview da imagem
        };
        reader.readAsDataURL(input.files[0]);

        // Envia a imagem para o servidor automaticamente
        var formData = new FormData();
        formData.append('nova_foto', input.files[0]);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'upload_foto.php', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    console.log('Foto enviada com sucesso!');

                    var novoCaminho = 'uploads/fotos_tutores/' + response.nova_foto;
                    preview.src = novoCaminho + '?' + new Date().getTime(); // Força o recarregamento da nova imagem
                } else {
                    console.log('Erro: ' + response.error);
                }
            } else {
                console.log('Erro ao enviar a foto.');
            }
        };

        xhr.send(formData);
    }
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


// Editar Bio

const editButton = document.querySelector('.editar-bio');
const saveButton = document.querySelector('.salvar-bio');
const bioText = document.querySelector('.bioText');
const bioInput = document.querySelector('.bioInput');

editButton.addEventListener('click', function () {
    if (bioInput.style.display === 'none') {
        bioText.style.display = 'none';
        bioInput.style.display = 'block';
        saveButton.style.display = 'block';
        bioInput.value = bioText.textContent.trim();
    } else {
        bioText.textContent = bioInput.value.trim();
        bioText.style.display = 'block';
        bioInput.style.display = 'none';
        saveButton.style.display = 'none';
    }
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
                    petDiv.querySelector('.cancelar-btn').style.display = 'none';
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
                    alert('CEP não encontrado!');
                }
            })
            .catch(error => {
                console.error('Erro ao buscar o CEP:', error);
                alert('Erro ao buscar o CEP. Verifique sua conexão e tente novamente.');
            });
    } else {
        alert('CEP inválido. Use apenas 8 dígitos numéricos.');
    }
});

document.querySelectorAll('.btn-salvar').forEach(button => {
    button.addEventListener('click', function () {
        const tutorDiv = this.closest('.informacoes-pessoais');

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

// Verifica se tem um endereço cadastrado

document.addEventListener('DOMContentLoaded', function () {
    const endereco = document.querySelector('.cepInput').value.trim();
   
    // Verifica se o endereço já está cadastrado
    if (endereco === '') {
        // Exibe a mensagem pedindo para cadastrar o endereço
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

// Suporte
// Função para mostrar e ocultar respostas
function toggleAnswer(question) {
    const answer = question.nextElementSibling; // Seleciona o elemento irmão que é a resposta
    answer.classList.toggle('show'); // Alterna a classe 'show' na resposta
}

// Mostra a primeira seção por padrão e adiciona eventos
document.addEventListener('DOMContentLoaded', () => {
    showContent('conteudo-1', document.querySelector('.sidebar nav ul li a'));

    // Adiciona eventos de clique para as perguntas de suporte
    const faqQuestions = document.querySelectorAll('.questions-container .question button');
    faqQuestions.forEach(question => {
        question.addEventListener('click', function () {
            toggleAnswer(this); // Passa a pergunta clicada para a função
            const icon = this.querySelector('.d-arrow');
            icon.classList.toggle('rotate'); // Rotaciona o ícone de seta
        });
    });
});

