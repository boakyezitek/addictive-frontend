$(".mobile_main_menu").on("click", function() {
    $(".my-slider").toggle();
    $(".mobile__menu__box").toggle();
});
$(".mobile_search_icon").on("click", function() {
    $('.menu-icons').hide();
    $('.mobile_search_icon').hide();
    $('.close-mobile-icon').show();
    $('.mobile-search-box').show();

});

$(".close-mobile-icon").on("click", function() {
    $('.menu-icons').show();
    $('.mobile_search_icon').show();
    $('.close-mobile-icon').hide();
    $('.mobile-search-box').hide();

});

// $(".mobile-search-box").on('click', function(){
//     $(".mobile__drop__down").css("display", "inline").fadeIn(2000);

// });

// $(".smobile__drop__down").on('mouseover', function(){
//       $(".mobile__drop__down").css("display", "inline").fadeIn(2000);
// });

