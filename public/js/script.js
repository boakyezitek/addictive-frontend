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


$("#searchBar").on('click', function(){
  $(".search__drop__down__box").css("display", "inline").fadeIn(2000);

});
$(".search__drop__down__box").on('mouseover', function(){
    $(".search__drop__down__box").css("display", "inline").fadeIn(2000);
  });

$("#searchBar").on('mouseout', function(){
  $(".search__drop__down__box").css("display", "none").fadeOut(2000);
});




// $("#myButton").on('click',  function () {
//   location.href = "/manuscript";
// });
// $(".right__main__content__btn").on('click',  function () {
//   location.href = "/condition";
// });
// $(".logo").on('click',  function () {
//   location.href = "/";
// });



$(".dropdown__menu").on('click', function(){
  $(".btn__dropup").toggleClass("show__arrow");
  $('.btn__dropdown').toggleClass("hide__arrow");
  const element = document.querySelector(".dropdown__list");
  element.classList.add("animate__animated", "animate__slideInLeft");
  element.style.setProperty('--animate-duration', '1s');
  $('.dropdown__list').toggle();

})
var selector = '.sidebar__content ul li';
$(selector).on('click', function(){
  $(selector).removeClass('active');
  $(this).addClass('active');
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
