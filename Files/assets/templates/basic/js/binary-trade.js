(function ($) {
  "use strict";

  /* ==================== Navbar Horizontal Scrolling JS Start ================== */
  function setIntersectionObserver(element, btn, options) {
    let defaultOptions = {
      rootMargin: "1px",
      threshold: 1,
      ...options,
    };

    let observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        entry.intersectionRatio >= 1
          ? $(btn).removeClass("show")
          : $(btn).addClass("show");
      });
    }, defaultOptions);

    return observer.observe(element);
  }

  function initializeNavHorizontal(nav) {
    let navPrev = $(nav).find(".nav-horizontal__btn.prev");
    let navNext = $(nav).find(".nav-horizontal__btn.next");
    let navMenu = $(nav).find(".nav-horizontal-menu");
    let navMenuItems = $(nav).find(
      ".nav-horizontal-menu .nav-horizontal-menu__item"
    );
    let navMenuItemFirst = $(nav).find(
      ".nav-horizontal-menu .nav-horizontal-menu__item:first-child"
    );
    let navMenuItemLast = $(nav).find(
      ".nav-horizontal-menu .nav-horizontal-menu__item:last-child"
    );
    let navMenuItemTotalWidth = 0;
    let navMenuScrollLeft = 0;

    setIntersectionObserver(navMenuItemFirst[0], navPrev[0], {
      root: navMenu[0],
    });

    setIntersectionObserver(navMenuItemLast[0], navNext[0], {
      root: navMenu[0],
    });

    navMenu[0].scrollLeft = 0;
    navMenuItems.each(
      (index, element) => (navMenuItemTotalWidth += element.scrollWidth)
    );
    navMenuScrollLeft = Math.floor(navMenuItemTotalWidth / navMenuItems.length);

    navNext.on("click", function () {
      navMenu[0].scrollLeft += navMenuScrollLeft;
    });

    navPrev.on("click", function () {
      if (navMenu[0].scrollLeft === 0) {
        return;
      }

      navMenu[0].scrollLeft -= navMenuScrollLeft;
    });
  }

  $(".nav-horizontal").each((index, nav) => initializeNavHorizontal(nav));
  /* ==================== Navbar Horizontal Scrolling JS End ==================== */

  /* ==================== Asset Compact Card Close JS Start ========================= */
  function closeAssetCompactCard() {
    let item = $(this).parents("li.nav-horizontal-menu__item");
    let navHorizontal = item.parents(".nav-horizontal");
    item.remove();
    initializeNavHorizontal(navHorizontal[0]);
  }

  $(document)
    .find(".asset-compact-card")
    .each((index, card) => {
      let close = $(card).find(".asset-compact-card__close");

      close.on("click", closeAssetCompactCard);
    });
  /* ==================== Asset Compact Card Close JS End ========================= */

  /* ==================== Trade Right JS Start ======================== */
  $('.trade-right-toggle').each(function (index, toggler) {
    let sidebar = $('.trade-section__right');
    let sidebarClose = sidebar.find('.btn--close');
    let sidebarOverlay = $('.sidebar-overlay');

    let hideSidebar = function () {
      sidebar.removeClass('show');
      sidebarOverlay.removeClass('show');
      $(toggler).removeClass('active');
      $('body').removeClass('scroll-hide');
      $(document).unbind('keydown', EscSidbear);
    }

    let EscSidbear = function (e) {
      if (e.keyCode === 27) {
        hideSidebar();
      }
    }

    let showSidebar = function () {
      $(toggler).addClass('active');
      sidebar.addClass('show');
      sidebarOverlay.addClass('show');
      $('body').addClass('scroll-hide');
      $(document).on('keydown', EscSidbear);
    }

    $(toggler).on('click', showSidebar);
    $(sidebarOverlay).on('click', hideSidebar);
    $(sidebarClose).on('click', hideSidebar);
  });
  /* ==================== Trade Right JS End ========================== */
})(jQuery);
