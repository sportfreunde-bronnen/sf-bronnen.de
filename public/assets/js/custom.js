(function($) {

  "use strict";

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

  // MODERN HEADER: the sticky main bar gets compact (and casts a shadow) once the page is scrolled.
  // Two thresholds (hysteresis), so the changing height of the bar can not make it flicker around one position.
  var $siteHeader = $('#siteHeader');

  if ($siteHeader.length) {
    var headerCompact = false;
    var headerTicking = false;

    var updateHeader = function () {
      var toTop = window.pageYOffset || document.documentElement.scrollTop;

      if (!headerCompact && toTop > 80) {
        headerCompact = true;
        $siteHeader.addClass('is-scrolled');
      } else if (headerCompact && toTop < 30) {
        headerCompact = false;
        $siteHeader.removeClass('is-scrolled');
      }

      headerTicking = false;
    };

    window.addEventListener('scroll', function () {
      if (!headerTicking) {
        headerTicking = true;
        window.requestAnimationFrame(updateHeader);
      }
    }, { passive: true });

    updateHeader();
  }

  // REMOVE # FROM URL
  $( 'a[href="#"]' ).click( function(event) {
      //event.preventDefault();
  });	
  
  // ACCORDION
  var $active = $("#accordion .collapse.show")
                  .prev()
                  .addClass("active");
  
  var $active1 = $("#accordion-help .collapse.show")
                  .prev()
                  .addClass("active");
                  
  $active
      .find("a")
      .append("<span class=\"fa fa-minus float-end\"></span>");
  
  $active1
      .find("a")
      .append("<span class=\"fa fa-minus float-start\"></span>");
      
  $("#accordion .card-header")
      .not($active)
      .find('a')
      .prepend("<span class=\"fa fa-plus float-end\"></span>");
  
  $("#accordion-help .card-header")
      .not($active1)
      .find('a')
      .prepend("<span class=\"fa fa-plus float-start\"></span>");
  
  $("#accordion, #accordion-help").on("shown.bs.collapse", function (e) {
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
  
  $("#accordion, #accordion-help").on("hidden.bs.collapse", function (e) {
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