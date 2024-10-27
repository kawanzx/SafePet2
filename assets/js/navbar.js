const menuToggle = document.querySelector('.menu-toggle');
const navList = document.querySelector('.nav-list');

menuToggle.addEventListener('click', () => {
    navList.classList.toggle('show');
    menuToggle.classList.toggle('open');
});

document.getElementById('logoutLink').addEventListener('click', function (event) {
    event.preventDefault();

    var confirmation = window.confirm("Você realmente deseja sair?");
    if (confirmation) {
        window.location.href = "/auth/logout.php";
    }
});