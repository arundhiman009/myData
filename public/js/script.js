$('.banner-carousel').owlCarousel({
	navigation : true, 
    singleItem: true,
    pagination: false,
    loop:true,
    autoplay:true,  
    autoplayTimeout:1000,
    autoplayHoverPause:true
})
$( ".owl-prev").html('<i class="las la-arrow-left"></i>');
$( ".owl-next").html('<i class="las la-arrow-right"></i>');



$(document).ready(function(){
    $('input').focus(function(){
      $(this).parent().find(".label-txt").addClass('label-active');
    });
  
    $("input").focusout(function(){
      if ($(this).val() == '') {
        $(this).parent().find(".label-txt").removeClass('label-active');
      };
    });
  
  });
  