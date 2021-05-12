$(".mobile_main_menu").on("click", function() {
    $(".mobile-nav").css("background-color",background);
    $('#toggle-menu').hide();
    $('#hide-menu').show();
    $('.mobile_main_logo').hide();
    $('.mobile_main_logo_toggle').show();


});

$("#hide-menu").on("click", function() {
    $('#toggle-menu').show();
    $('#hide-menu').hide();
    $(".mobile-nav").css("background-color","#fff");
    $(".mobile__menu__box").toggle();
    $(".mobile_search_icon").show();
    $('.mobile_main_logo').show();
    $('.mobile_main_logo_toggle').hide();
});


$("#mobile-searchBar").on('focus', function(){
    $(".mobile__search__drop__down__box").css("display", "block").fadeIn(2000);

});


$("#searchBar").on('mouseout', function(){
    $(".search__drop__down__box").css("display", "none").fadeOut(2000);
  });


$(".mobile_search_icon").on("click", function() {
    $('.menu-icons').hide();
    $('.mobile-search-box').show();
    $('.mobile_search_icon').hide();
    $('.mobile_close_icon').show();

});


$(".mobile_close_icon").on("click", function() {
    $('.menu-icons').show();
    $('.mobile-search-box').hide();
    $('.mobile_search_icon').show();
    $('.mobile_close_icon').hide();

});

$('.mobile').on('click', function(){
    const element = document.querySelector(".col-md-2");
    element.classList.add("animate__animated", "animate__slideInDown");
    element.style.setProperty('--animate-duration', '0.8s');
    $('.col-md-2').show();
  })

  $('.close__button').on('click', function(){
    $('.col-md-2').hide();
  })

  $(".mobile_main_logo_toggle").on('click',  function () {
    location.href = "/";
  });
