document.addEventListener('DOMContentLoaded', () => {

function toggleAnswer(question) {
    const answer = question.nextElementSibling; 
    if (answer) {
        answer.classList.toggle('show'); 
    }
}

    showContent('conteudo-1', document.querySelector('.sidebar nav ul li a'));

    const faqQuestions = document.querySelectorAll('.questions-container .question button');
    faqQuestions.forEach(question => {
        question.addEventListener('click', function () {
            toggleAnswer(this); 
            const icon = this.querySelector('.d-arrow');
            icon.classList.toggle('rotate'); 
        });
    });
});
