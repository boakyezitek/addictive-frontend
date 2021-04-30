$(".my-slider").slick({
  centerMode: true,
  slidesToShow: 1,
  dots: true,
  autoplay: true,
  autoplaySpeed: 2000,
  centerPadding: "40px",
  responsive: [
      // {
      //     breakpoint: 1920,
      //     settings: {
      //       arrows: false,
      //       centerMode: true,
      //       centerPadding: '20px',
      //       slidesToShow: 1
      //     }
      //   },

      //   {
      //     breakpoint: 1360,
      //     settings: {
      //       arrows: false,
      //       centerMode: true,
      //       centerPadding: '20px',
      //       slidesToShow: 1
      //     }
      //   },
      {
          breakpoint: 320,
          settings: {
              arrows: false,
              centerMode: true,
              centerPadding: "180px",
              slidesToShow: 1
          }
      }
  ]
});


$("#toggle-search").on("click", function() {
  $(".main-search-box").show();
  const element = document.querySelector(".main-search-box");
  element.classList.add("animate__animated", "animate__slideInDown");
  element.style.setProperty('--animate-duration', '0.2s');
  $('#hide-search').show();
  $(".add_main_menu").hide();
  $(".add_outline_btn").hide();
  $(".add_social").hide();
  $('#toggle-search').hide();
});

$("#hide-search").on("click", function() {
  $(".main-search-box").hide();
  $('#hide-search').hide();
  $(".add_main_menu").show();
  $(".add_outline_btn").show();
  $(".add_social").show();
  $('#toggle-search').show();
});


$("#searchBar").on('focus', function(){
  $(".search__drop__down__box").css("display", "inline").fadeIn(2000);
});


$("#searchBar").on('mouseout', function(){
  $(".search__drop__down__box").css("display", "none").fadeOut(2000);
});

$(".dropdown__menu").on('click', function(){
    $(".btn__dropup").toggleClass("show__arrow");
    $('.btn__dropdown').toggleClass("hide__arrow");
    const element = document.querySelector(".dropdown__list");
    element.classList.add("animate__animated", "animate__slideInLeft");
    element.style.setProperty('--animate-duration', '1s');
    $('.dropdown__list').toggle();

})
