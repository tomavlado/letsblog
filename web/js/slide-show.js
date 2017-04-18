
$(window).load(function () {

    //slide show
    var imgs = [
        '../images/1.jpg',
        '../images/2.jpg',
        '../images/3.jpg',
        '../images/4.jpg',
        '../images/5.png'
    ];

    var counter = 0;

    var currImg = new Image();

    currImg.src = imgs[counter];

    $('.slides').prepend("<img src=" + currImg.src + ">");

    setInterval(function () {

        $('.slides').find('img').fadeOut(500, function () {

            counter < imgs.length - 1 ? counter++ : counter = 0;

            $('.slides').find('img').attr('src', imgs[counter]).fadeIn(500);

        });

    }, 6000);

});

$(document).ready(function () {

    function resizeImg(imgClass) {
        $('.' + imgClass + ' img').each(function() {
            if($(this).width() > $(this).height()){
                $(this).removeClass('wide');
                $(this).addClass('tall');
            }
        });
    }

    resizeImg('profile-picture');
    resizeImg('post-prof-img');
    resizeImg('full-post-image')

    //show paragraph on hover
    $('.post-cont').hover(function () {
       $(this).addClass('hoverin-cont');
       $(this).find('p').css('visibility', 'visible');
       $(this).children().not('p').css('opacity', 0.3);
    }, function () {
       $(this).removeClass('hoverin-cont');
       $(this).find('p').css('visibility', 'hidden');
        $(this).children().not('p').css('opacity', 1);
    });


});