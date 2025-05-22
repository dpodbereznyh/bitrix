<script>
    
var sliderIsLive;
window.addEventListener("resize", function() {
  if (window.innerWidth > 920) {
    $(".tabs__header_row .tabs__header").slick('unslick');
    sliderIsLive = false;
  }
  else {
    if (!sliderIsLive) {
      $(".tabs__header_row .tabs__header").slick({
        centerPadding: 24,
        slidesToScroll: 1,
        arrows: true,
        dots: false,
        variableWidth: true,
        infinite: false,
        centerMode: false,
        centerPadding:'50px'
    });
      sliderIsLive = true;
    }
  }
});

</script>
