/* Sidebar */
document.addEventListener('DOMContentLoaded', function() {
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
            submenu.style.display = 'none'; // Esconde todos os submenus
        });
    }
});
});

// Função para mostrar o conteúdo
function showContent(sectionId, element) {

    var sidebar = document.querySelector('.sidebar');

    if (!sidebar) {
        console.error("Sidebar não encontrada!");
        return;
    }

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
        var subContentId = element.dataset.subContent; // Obtém o ID do sub-conteúdo

        // Oculta todos os sub-conteúdos
        var subSections = document.querySelectorAll('#' + sectionId + ' > div');
        subSections.forEach(function (subSection) {
            subSection.style.display = 'none'; // Oculta todos os sub-conteúdos
        });

        // Exibe o sub-conteúdo selecionado
        if (subContentId) {
            var activeSubSection = document.getElementById(subContentId);
            if (activeSubSection) {
                activeSubSection.style.display = 'block'; // Exibe o sub-conteúdo
            }
        }
        return; // Não faz nada, apenas retorna
    }

    // Após a seleção do conteúdo, recolhe a sidebar se não for um submenu
    if (sidebar.classList.contains('sidebar-expandida')) {
        sidebar.classList.remove('sidebar-expandida'); // Recolhe a sidebar
    }
}

// Função para alternar a exibição do submenu
function alternarSubmenu(event) {
    event.preventDefault();
    const submenu = event.currentTarget.nextElementSibling;

    // Alterna entre mostrar e ocultar o submenu
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

                    var novoCaminho = 'uploads/fotos_cuidadores/' + response.nova_foto;
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

// Editar Bio

document.addEventListener('DOMContentLoaded', function() {
    const editButton = document.querySelector('.editar-bio');
    const saveButton = document.querySelector('.salvar-bio');
    const bioText = document.querySelector('.bioText');
    const bioInput = document.querySelector('.bioInput');

    if (editButton && saveButton && bioText && bioInput) {
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
    } else {
        console.error("Um ou mais elementos não foram encontrados no DOM.");
    }
});

//Informações pessoais cuidador

document.addEventListener('DOMContentLoaded', function () {
    const editar = document.querySelector('.btn-editar');
    const nomeCuidadorText = document.querySelector('.nome-cuidadorText');
    const nomeCuidadorInput = document.querySelector('.nome-cuidadorInput');
    const enderecoText = document.querySelector('.enderecoText');
    const enderecoInput = document.querySelector('.enderecoInput');
    const telefoneText = document.querySelector('.telefoneText');
    const telefoneInput = document.querySelector('.telefoneInput');
    const emailText = document.querySelector('.emailText');
    const emailInput = document.querySelector('.emailInput');
    const dt_nascimentoText = document.querySelector('.dt_nascimentoText');
    const dt_nascimentoInput = document.querySelector('.dt_nascimentoInput');

    if (editar) {
        editar.addEventListener('click', function () {
            if (nomeCuidadorInput.style.display === 'none') {
                nomeCuidadorText.style.display = 'none';
                nomeCuidadorInput.style.display = 'block';
                nomeCuidadorInput.value = nomeCuidadorText.textContent.trim();
            } else {
                nomeCuidadorText.textContent = nomeCuidadorInput.value.trim();
                nomeCuidadorText.style.display = 'block';
                nomeCuidadorInput.style.display = 'none';
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
    } else {
        console.error("O botão de editar não foi encontrado no DOM.");
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const salvarButtons = document.querySelectorAll('.btn-salvar');

    if (salvarButtons.length > 0) {
        salvarButtons.forEach(button => {
            button.addEventListener('click', function () {
                const cuidadorDiv = this.closest('.informacoes-pessoais');

                // Obter os valores dos inputs
                const nome = cuidadorDiv.querySelector('.nome-cuidadorInput').value.trim();
                const endereco = cuidadorDiv.querySelector('.enderecoInput').value.trim();
                const telefone = cuidadorDiv.querySelector('.telefoneInput').value.trim();
                const email = cuidadorDiv.querySelector('.emailInput').value.trim();
                const dt_nascimento = cuidadorDiv.querySelector('.dt_nascimentoInput').value.trim();

                let valid = true;

                // Limpar mensagens de erro anteriores
                cuidadorDiv.querySelectorAll('.erro').forEach(span => span.textContent = '');

                // Validação do nome
                if (nome === '' || nome.length < 2) {
                    cuidadorDiv.querySelector('#nomeErro').textContent = 'Nome deve ter pelo menos 2 caracteres.';
                    valid = false;
                }

                // Validação do endereço
                if (endereco === '') {
                    cuidadorDiv.querySelector('#enderecoErro').textContent = 'Endereço é obrigatório.';
                    valid = false;
                }

                // Validação do telefone (10 ou 11 dígitos, sem caracteres especiais)
                const telefonePattern = /^[0-9]{10,11}$/;
                if (!telefonePattern.test(telefone)) {
                    cuidadorDiv.querySelector('#telefoneErro').textContent = 'Telefone inválido. Use 10 ou 11 dígitos numéricos.';
                    valid = false;
                }

                // Validação do e-mail
                const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!emailPattern.test(email)) {
                    cuidadorDiv.querySelector('#emailErro').textContent = 'E-mail inválido.';
                    valid = false;
                }

                // Validação da data de nascimento
                if (dt_nascimento === '') {
                    cuidadorDiv.querySelector('#dtNascimentoErro').textContent = 'Data de nascimento é obrigatória.';
                    valid = false;
                }

                if (valid) {
                    const cuidadorId = cuidadorDiv.getAttribute('cuidador-id');
                    // Enviar os dados via AJAX
                    const formData = new FormData();
                    formData.append('cuidadorId', cuidadorId);
                    formData.append('nome-cuidador', nome);
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
                                cuidadorDiv.querySelector('.nome-cuidadorText').textContent = nome;
                                cuidadorDiv.querySelector('.enderecoText').textContent = endereco;
                                cuidadorDiv.querySelector('.telefoneText').textContent = telefone;
                                cuidadorDiv.querySelector('.emailText').textContent = email;
                                cuidadorDiv.querySelector('.dt_nascimentoText').textContent = dt_nascimento;

                                // Ocultar os inputs e exibir os textos
                                cuidadorDiv.querySelectorAll('.nome-cuidadorText, .enderecoText, .telefoneText, .emailText, .dt_nascimentoText').forEach(text => {
                                    text.style.display = 'inline';
                                });
                                cuidadorDiv.querySelectorAll('.nome-cuidadorInput, .enderecoInput, .telefoneInput, .emailInput, .dt_nascimentoInput').forEach(input => {
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
    } else {
        console.error("Nenhum botão de salvar encontrado no DOM.");
    }
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


