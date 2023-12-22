$(function() {
  $('.carousel').slick({
    infinite: false,
    variableWidth: true,
    slidesToShow: 1,
    rtl: rtl_slick()
  });
  
$('.carousel').on('afterChange', function(event, slick, currentSlide, nextSlide){
  console.log(currentSlide);
});

});