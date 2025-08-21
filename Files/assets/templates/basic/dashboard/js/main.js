(function ($) {
  "use strict";

  let allowDecimal = window.allow_decimal || 4;

  // ============== Header Hide Click On Body Js Start ========
  $('.header-button').on('click', function () {
    $('.body-overlay').toggleClass('show')
  });
  $('.body-overlay').on('click', function () {
    $('.header-button').trigger('click')
    $(this).removeClass('show');
  });
  // =============== Header Hide Click On Body Js End =========

  // ==========================================
  //      Start Document Ready function
  // ==========================================
  $(document).ready(function () {

    // ========================== Header Hide Scroll Bar Js Start =====================
    $('.navbar-toggler.header-button').on('click', function () {
      $('body').toggleClass('scroll-hide')
    });
    $('.body-overlay').on('click', function () {
      $('body').removeClass('scroll-hide')
    });
    // ========================== Header Hide Scroll Bar Js End =====================

    // ========================== Toggle Search Box Js Start =====================
    $('.toggle-search').on('click', function () {
      $('.toggle-search__box').addClass('show')
      $('body').addClass('scroll-hide')
    });
    $('.toggle-search__close').on('click', function () {
      $('.toggle-search__box').removeClass('show')
      $('body').removeClass('scroll-hide')
    });
    // ========================== Toggle Search Box Js End =====================

    // ================== Password Show Hide Js Start ==========
    $(".toggle-password").on('click', function () {
      $(this).toggleClass(" fa-eye-slash");
      var input = $($(this).attr("id"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
    // =============== Password Show Hide Js End =================

    // ===================== Table Delete Column Js Start =================
    $('.delete-icon').on('click', function () {
      $(this).closest('tr').addClass('d-none')
    });
    // ===================== Table Delete Column Js End =================

    // =================19. Increament & Decreament Js Start ======
    const productQty = $(".qty");
    productQty.each(function () {
      const qtyIncrement = $(this).find(".qty__increment");
      const qtyDecrement = $(this).find(".qty__decrement");
      let qtyValue = $(this).find(".qty__value");
      qtyIncrement.on("click", function () {
        var oldValue = parseFloat(qtyValue.val());
        var newVal = oldValue + 1;
        qtyValue.val(newVal).trigger("change");
      });
      qtyDecrement.on("click", function () {
        var oldValue = parseFloat(qtyValue.val());
        if (oldValue <= 0) {
          var newVal = oldValue;
        } else {
          var newVal = oldValue - 1;
        }
        qtyValue.val(newVal).trigger("change");
      });
    });

    // =========show more js end here=========

    // ============================ToolTip Js Start=====================
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    // ============================ToolTip Js End========================

    // ================== Sidebar Menu Js Start ===============
    $(".has-dropdown > a").click(function () {
      $(".sidebar-submenu").slideUp(200);
      if (
        $(this)
          .parent()
          .hasClass("active")
      ) {
        $(".has-dropdown").removeClass("active");
        $(this)
          .parent()
          .removeClass("active");
      } else {
        $(".has-dropdown").removeClass("active");
        $(this)
          .next(".sidebar-submenu")
          .slideDown(200);
        $(this)
          .parent()
          .addClass("active");
      }
    });
    // Sidebar Icon & Overlay js 

    $(".dashboard-sidebar-filter__button").on("click", function () {
      $(".sidebar-menu").addClass('show-sidebar');
      $(".sidebar-overlay").addClass('show');
    });
    $(".sidebar-menu__close, .sidebar-overlay").on("click", function () {
      $(".sidebar-menu").removeClass('show-sidebar');
      $(".sidebar-overlay").removeClass('show');
    });

    $(".toggle-dashboard-right").on("click", function () {
      $(".dashboard-right").toggleClass('show');
    });

    $(".right-sidebar__close, .sidebar-overlay").on("click", function () {
      $(".right-sidebar").removeClass('show-rightbar');
      $(".sidebar-overlay").removeClass('show');
    });
    // Sidebar Icon & Overlay js 
    // ===================== Sidebar Menu Js End =================

    // ==================== Dashboard User Profile Dropdown Start ==================
    $('.user-info__button').on('click', function () {
      $('.user-info-dropdown').toggleClass('show');
    });
    $('.user-info__button').attr('tabindex', -1).focus();

    $('.user-info__button').on('focusout', function () {
      $('.user-info-dropdown').removeClass('show');
    });
    // ==================== Dashboard User Profile Dropdown End ==================

  });
  // ==========================================
  //      End Document Ready function
  // ==========================================

  // ========================= Preloader Js Start =====================
  $(window).on("load", function () {
    $('.preloader').fadeOut();
  })
  // ========================= Preloader Js End=====================

  // ========================= Header Sticky Js Start ==============
  $(window).on('scroll', function () {
    if ($(window).scrollTop() >= 300) {
      $('.header').addClass('fixed-header');
    }
    else {
      $('.header').removeClass('fixed-header');
    }
  });
  // ========================= Header Sticky Js End===================

  //============================ Scroll To Top Icon Js Start =========
  var btn = $('.scroll-top');

  $(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
      btn.addClass('show');
    } else {
      btn.removeClass('show');
    }
  });

  btn.on('click', function (e) {
    e.preventDefault();
    $('html, body').animate({ scrollTop: 0 }, '300');
  });
  //========================= Scroll To Top Icon Js End ======================

  $('.copyTextBtn').on('click', function (e) {
    var copyText = $(this).parent().find('.copyText');
    copyText = copyText[0];
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    /*For mobile devices*/
    document.execCommand("copy");
    copyText.blur();


    $(this).html(`
      <span class="copy-link__icon"><i class="las la-check"></i></span>
    `);

    $(this).attr('data-bs-original-title', "Copied!").tooltip('show');

    setTimeout(() => {
      $(this).tooltip('hide').attr('data-bs-original-title', "Copy URL")
      $(this).html(`
        <span class="copy-link__icon"><i class="las la-copy"></i></span>
      `);
      if (window.getSelection) { window.getSelection().removeAllRanges(); }
      else if (document.selection) { document.selection.empty(); }
    }, 1000);
  });


  $(".p2p-sidebar__menu").on("click", function () {
    $(".p2p-sidebar").addClass('show-sidebar');
    $(".sidebar-overlay").addClass('show');
  });
  $(".p2p-sidebar__close").on("click", function () {
    $(".p2p-sidebar").removeClass('show-sidebar');
    $(".sidebar-overlay").removeClass('show');
  });

  $.each($('.select2'), function (index, element) {
    $(element).select2({
      dropdownParent: $(this).closest('.position-relative')
    });
  });

  // trade btn js start here 

  $(document).on('click', '.allTradeBtn', function(e) {
    e.stopPropagation(); 
    const $tradeBtnWrapper = $(this).closest('.trade-btn-wrapper');
    $('.trade-list').not($tradeBtnWrapper.find('.trade-list')).removeClass('show');
    $tradeBtnWrapper.find('.trade-list').toggleClass('show');
});

$(document).on('click', function(e) {
    if (!$(e.target).closest('.trade-btn-wrapper').length) {
        $('.trade-list').removeClass('show');
    }
});

// trade btn js end here 
  
})(jQuery);

function showAmount(amount, allowDecimal, separate = true, exceptZeros = false) {
  let separator = '';
  if (separate) {
    separator = ',';
  }

  amount = parseFloat(amount).toFixed(allowDecimal).split('.');
  let printAmount = amount[0].replace(/\B(?=(\d{3})+(?!\d))/g, separator);
  printAmount = printAmount + '.' + amount[1];

  if (exceptZeros) {
    let exp = printAmount.split('.');
    if (Number(exp[1]) * 1 === 0) {
      printAmount = exp[0];
    } else {
      printAmount = printAmount.replace(/(\.[0-9]*[1-9])0+$/, '$1');
    }
  }
  return printAmount;
}

function getAmount(amount) {
  return parseFloat(amount).toFixed(4)
}

$('.submit-form-on-change').on('change', function (e) {
  $(this).closest('form').submit();
});
setTimeout(() => {
  $('.skeleton').removeClass('skeleton');
}, 1500);

