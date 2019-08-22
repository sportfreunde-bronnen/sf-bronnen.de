(function($) {

  "use strict";

  // We listen to the resize event
  window.addEventListener('resize', () => {
    // We execute the same script as before
    let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
  });

  if (window.innerWidth >= 768) {

    $(window).scroll(function () {
      var toTop = $(window).scrollTop();
      var nav = $('nav');
      var departmentViewScroll = $('#departmentViewScroll');

      if (toTop > 60 && departmentViewScroll.hasClass('invisible')) {
        departmentViewScroll.removeClass('invisible');
      }

      if (toTop <= 60 && !departmentViewScroll.hasClass('invisible')) {
        departmentViewScroll.addClass('invisible');
      }

      if (toTop > 188 && !nav.hasClass('fixed-top')) {
        nav.addClass('fixed-top');

      }
      if (toTop <= 100 && nav.hasClass('fixed-top')) {
        nav.removeClass('fixed-top');
      }
    });

  }

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
      navigationText : ["<span class='fa fa-chevron-left animation'></span>","<span class='fa fa-chevron-right animation'></span>"],
      responsiveClass: true,
      responsive: {
        0: {
          items: 1,
          nav: true
        },
        768: {
          items: 2,
          nav: true
        },
        1200: {
          items: 3,
          nav: true
        }
      }
  });

  $('div.owl-nav').addClass('text-center');

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