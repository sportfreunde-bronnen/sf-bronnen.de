(function($) {

  "use strict";

  if (window.innerWidth >= 768) {

    $(window).scroll(function () {
      var toTop = $(window).scrollTop();
      var nav = $('nav');
      var departmentViewScroll = $('#departmentViewScroll');

      console.log(toTop);

      if (toTop > 60 && departmentViewScroll.hasClass('invisible')) {
        departmentViewScroll.removeClass('invisible');
      }

      if (toTop <= 60 && !departmentViewScroll.hasClass('invisible')) {
        departmentViewScroll.addClass('invisible');
      }

      if (toTop > 188 && !nav.hasClass('fixed-top')) {
        nav.addClass('fixed-top');
        nav.addClass('op-50');
        console.log("ADD");

      }
      if (toTop <= 100 && nav.hasClass('fixed-top')) {
        nav.removeClass('fixed-top');
        nav.removeClass('op-50');
        console.log("REMOVE");
      }
    });

  }

    // Backstretch Header Full
	$(".header-full").backstretch([
        "images/slider/slider-full-img1.jpg", 
        "images/slider/slider-full-img2.jpg", 
        "images/slider/slider-full-img3.jpg"
  ], {duration: 4000, fade: 750, fadeFirstImage:false});
  
  $(".header-full #next").click(function(x) {
      x.preventDefault();
      $(".header-full").data("backstretch").next();
  });
  $(".header-full #prev").click(function(x) {
      x.preventDefault();
      $(".header-full").data("backstretch").prev();
  });
  
  // REMOVE # FROM URL
  $( 'a[href="#"]' ).click( function(event) {
      //event.preventDefault();
  });	
  
  // Blog Carousel
  $("#blog-post-carousel").owlCarousel({
      autoPlay: true, //Set AutoPlay to 3 seconds
      items : 3,
      stopOnHover : true,
      navigation : true, // Show next and prev buttons
      pagination : false,
      navigationText : ["<span class='fa fa-chevron-left animation'></span>","<span class='fa fa-chevron-right animation'></span>"]
  });

  // ACCORDION
  var $active = $("#accordion .collapse.show, #accordion-downloads .collapse.show")
                  .prev()
                  .addClass("active");
  
  var $active1 = $("#accordion-help .collapse.show")
                  .prev()
                  .addClass("active");
                  
  $active
      .find("a")
      .append("<span class=\"fa fa-minus float-right\"></span>");
  
  $active1
      .find("a")
      .append("<span class=\"fa fa-minus float-left\"></span>");
      
  $("#accordion .card-header, #accordion-downloads .card-header")
      .not($active)
      .find('a')
      .prepend("<span class=\"fa fa-plus float-right\"></span>");
  
  $("#accordion-help .card-header")
      .not($active1)
      .find('a')
      .prepend("<span class=\"fa fa-plus float-left\"></span>");
  
  $("#accordion, #accordion-downloads, #accordion-help").on("shown.bs.collapse", function (e) {
      $("#accordion .card-header.active, #accordion-faqs .card-header.active, #accordion-help .card-header.active")
          .removeClass("active")
          .find(".fa")
          .toggleClass("fa-plus fa-minus");				
      $(e.target)
          .prev()
          .addClass("active")
          .find(".fa")
          .toggleClass("fa-plus fa-minus");		
  });
  
  $("#accordion, #accordion-downloads, #accordion-help").on("hidden.bs.collapse", function (e) {
      $(e.target)
          .prev()
          .removeClass("active")
          .find(".fa")
          .removeClass("fa-minus")
          .addClass("fa-plus");
  });
  
  // Gallery FILTERS
  var $grid = $('#gallery-grid');
  $grid.shuffle({
      itemSelector: '.gallery-grid-item', // the selector for the items in the grid
      speed: 500 // Transition/animation speed (milliseconds)
  });
  /* reshuffle when user clicks a filter item */
  $('#gallery-filter li a').click(function (e) {
      // set active class
      $('#gallery-filter li a').removeClass('active');
      $(this).addClass('active');
      // get group name from clicked item
      var groupName = $(this).attr('data-group');
      // reshuffle grid
      $grid.shuffle('shuffle', groupName );
  });
  
  //MAGNIFIC POPUP
  $('#gallery-grid').magnificPopup({
      delegate: 'a', 
      type: 'image',
      gallery: {
          enabled: true
      }
  });
	
})(window.jQuery);