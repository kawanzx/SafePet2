$(document).ready(function(){
    $('.card-container').slick({
        dots: true,               // Exibe os pontos de navegação
        infinite: true,           // Permite navegação infinita
        speed: 500,               // Velocidade da transição
        slidesToShow: 3,          // Exibe 3 cards na versão desktop
        slidesToScroll: 1,        // Avança 1 card por vez
        autoplay: true,           // Habilita o autoplay
        autoplaySpeed: 3000,      // Tempo de pausa entre os slides (3 segundos)
        responsive: [
            {
                breakpoint: 1024,  // Para telas médias (tablets)
                settings: {
                    slidesToShow: 2,   // Exibe 2 cards
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 768,   // Para telas pequenas (celulares)
                settings: {
                    slidesToShow: 1,   // Exibe 1 card
                    slidesToScroll: 1,
                }
            }
        ]
    });
});
