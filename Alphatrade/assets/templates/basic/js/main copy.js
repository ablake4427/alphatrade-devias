(function ($) {
  "use strict";

  $(document).ready(function () {
    $(".header-button").on("click", function () {
      $(".body-overlay").toggleClass("show");
      $(".has-mega-menu").find(".mega-menu").removeClass("open");
    });
    $(".body-overlay").on("click", function () {
      $(".header-button").trigger("click");
      $(this).removeClass("show");
      $(".has-mega-menu").find(".mega-menu").removeClass("open");
    });

    $(".custom--dropdown > .custom--dropdown__selected").on(
      "click",
      function () {
        $(this).parent().toggleClass("open");
      }
    );

    $(".custom--dropdown > .dropdown-list > .dropdown-list__item").on(
      "click",
      function () {
        $(
          ".custom--dropdown > .dropdown-list > .dropdown-list__item"
        ).removeClass("selected");
        $(this)
          .addClass("selected")
          .parent()
          .parent()
          .removeClass("open")
          .children(".custom--dropdown__selected")
          .html($(this).html());
      }
    );

    $(document).on("keyup", function (evt) {
      if ((evt.keyCode || evt.which) === 27) {
        $(".custom--dropdown").removeClass("open");
      }
    });

    $(document).on("click", function (evt) {
      if (
        $(evt.target).closest(".custom--dropdown > .custom--dropdown__selected")
          .length === 0
      ) {
        $(".custom--dropdown").removeClass("open");
      }
    });

    $(".toggle-password").on("click", function () {
      $(this).toggleClass(" fa-eye-slash");
      var input = $(`input[name=password]`);
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });

    if ($(".mySwiper").length) {
      var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 20,
        autoplay: true,
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        breakpoints: {
          575: {
            slidesPerView: 2,
            spaceBetween: 20,
          },
          992: {
            slidesPerView: 4,
            spaceBetween: 40,
          },
        },
      });
    }

    // ========================= single Slider Js Start ===============
    if ($(".single-slider").length) {
      $(".single-slider").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        autoplay: true,
        fade: true,
        speed: 500,
        dots: false,
        arrows: false,
        cssEase: "linear",
      });
    }
    // ========================= single Slider Js End ===================
  });

  // ========================= Preloader Js Start =====================
  $(window).on("load", function () {
    $(".preloader-wrapper").fadeOut();
  });
  // ========================= Preloader Js End=====================

  // ========================= Header Sticky Js Start ==============
  $(window).on("scroll", function () {
    if ($(window).scrollTop() >= 300) {
      $(".header").addClass("fixed-header");
    } else {
      $(".header").removeClass("fixed-header");
    }
  });
  // ========================= Header Sticky Js End===================

  //============================ Scroll To Top Icon Js Start =========
  var btn = $(".scroll-top");

  $(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
      btn.addClass("show");
    } else {
      btn.removeClass("show");
    }
  });

  btn.on("click", function (e) {
    e.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, "300");
  });

  $(".buy-btn-sm").on("click", function () {
    $(".buy-sell-one").addClass("buy-sell-one-show");
    $(".sidebar-overlay").addClass("show");
  });

  // bottom button js two
  $(".sell-btn-sm").on("click", function () {
    $(".buy-sell-two").addClass("buy-sell-two-show");
    $(".sidebar-overlay").addClass("show");
  });

  $(".sidebar__close").on("click", function () {
    $(".buy-sell-two").removeClass("buy-sell-two-show");
    $(".buy-sell-one").removeClass("buy-sell-one-show");
    $(".sidebar-overlay").removeClass("show");
  });

  $(".has-mega-menu").on("click", function (e) {
    $(this).find(".mega-menu").toggleClass("open");
  });
})(jQuery);

let allowDecimal = window.allow_decimal || 4;

function showAmount(
  amount,
  decimal = allowDecimal,
  separate = true,
  exceptZeros = false
) {
  let separator = "";
  if (separate) {
    separator = ",";
  }

  amount = parseFloat(amount).toFixed(decimal).split(".");
  let printAmount = amount[0].replace(/\B(?=(\d{3})+(?!\d))/g, separator);
  printAmount = printAmount + "." + amount[1];

  if (exceptZeros) {
    let exp = printAmount.split(".");
    if (Number(exp[1]) * 1 === 0) {
      printAmount = exp[0];
    } else {
      printAmount = printAmount.replace(/(\.[0-9]*[1-9])0+$/, "$1");
    }
  }
  return printAmount;
}

function getAmount(amount) {
  return parseFloat(amount).toFixed(allowDecimal);
}

function tableDataLabel() {
  Array.from(document.querySelectorAll("table")).forEach((table) => {
    let heading = table.querySelectorAll("thead tr th");
    Array.from(table.querySelectorAll("tbody tr")).forEach((row) => {
      Array.from(row.querySelectorAll("td")).forEach((colum, i) => {
        colum.setAttribute(
          "data-label",
          heading[i] ? heading[i].innerText : ""
        );
      });
    });
  });
}

tableDataLabel();

var tooltipTriggerList = [].slice.call(
  document.querySelectorAll('[data-bs-toggle="tooltip"]')
);
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});
