function showContent(contentId, element) {
    // Esconde todas as seções
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => {
        section.classList.remove('active');
    });

    // Mostra a seção selecionada
    const selectedSection = document.getElementById(contentId);
    selectedSection.classList.add('active');

    // Muda a classe do link selecionado
    const links = document.querySelectorAll('.sidebar nav ul li a');
    links.forEach(link => {
        link.classList.remove('active-link');
    });
    element.classList.add('active-link');
}

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