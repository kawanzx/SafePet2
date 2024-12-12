$(document).ready(function(){
    $('.card-container').slick({
        dots: true,              
        infinite: true,          
        speed: 500,              
        slidesToShow: 3,          
        slidesToScroll: 1,       
        autoplay: true,           
        autoplaySpeed: 3000,      
        responsive: [
            {
                breakpoint: 1024, 
                settings: {
                    slidesToShow: 2,   
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 768,  
                settings: {
                    slidesToShow: 1,   
                    slidesToScroll: 1,
                }
            }
        ]
    });
});
