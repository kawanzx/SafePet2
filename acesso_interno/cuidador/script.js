/* NavBar confirmação de logout */
document.getElementById('logoutLink').addEventListener('click', function(event) {
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

    // Remove a classe 'active' de todos os links da sidebar
    var links = document.querySelectorAll('.sidebar a');
    links.forEach(function (link) {
        link.classList.remove('active');
    });

    // Adiciona a classe 'active' ao link clicado
    element.classList.add('active');
}

// exibir as respostas do suporte
document.querySelectorAll('.faq-question').forEach(item => {
    item.addEventListener('click', () => {
        const answer = item.nextElementSibling;
        answer.classList.toggle('active'); // Adiciona ou remove a classe active
    });
});
