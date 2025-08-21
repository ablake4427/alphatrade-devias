<div class="offcanvas offcanvas-end p-5" tabindex="-1" id="payment-method-canvas" aria-labelledby="offcanvasLabel">
    <div class="offcanvas-header">
        <h4 class="mb-0 fs-18 offcanvas-title"></h4>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa fa-times-circle"></i>
        </button>
    </div>
    <div class="offcanvas-body"></div>
</div>

@push('script')
    <script>
        "use strict";
        (function($) {

            let take = 20;
            let skip = 20;
            let isLoadMore = false;

            const URLBuilder = () => {

                let url =
                    "{{ route('p2p', ['type' => ':type', 'coin' => ':coin', 'currency' => ':currency', 'paymentMethod' => ':paymentMethod', 'amount' => ':amount', 'region' => ':region']) }}";
                const amount = $('.filter-amount').val() || '';
                const type = $('.buy-sell-tab').find(`button.active`).data('type');
                const coin = $('.coin-list').find(`.active`).data('symbol') || 'all';

                const region = ($(".country-dropdown").find('.has-value').data('value')) || (amount ? 'all' : '');
                const paymentMethod = ($(".payment-method-dropdown").find('.has-value').data('value')) || (amount ||
                    region ? 'all' : '');
                const currency = ($(".currency-dropdown").find('.has-value').data('value')) || (amount || region ?
                    'all' : '');

                url = url.replace(":type", type)
                    .replace(':coin', coin)
                    .replace(":paymentMethod", paymentMethod)
                    .replace(":amount", amount)
                    .replace(":region", region)
                    .replace(":currency", currency)
                    .replace("///", '');

                window.history.pushState({}, '', url);

                $.ajax({
                    url: url,
                    method: "GET",
                    data: {
                        take,
                        skip
                    },
                    beforeSend: function() {
                        $("#loadMore").find(`i`).addClass('fa-spin');
                        $("#loadMore").attr('disabled', true);
                        $('#ad-list-empty').removeClass('d-none');
                        $('#ad-list').addClass('d-none');
                    },
                    complete: function() {
                        setTimeout(() => {
                            $('#ad-list-empty').addClass('d-none');
                            $('#ad-list').removeClass('d-none');
                            $("#loadMore").find(`i`).removeClass('fa-spin');
                            $("#loadMore").attr('disabled', false);
                        }, 1000);
                    },
                    success: function(resp) {
                        if (resp.success) {
                            skip += take;
                            if (isLoadMore) {
                                $("#ad-list").append(resp.html);
                            } else {
                                $("#ad-list").html(resp.html);
                            }
                            tableDataLabel();
                            if (isLoadMore) {
                                window.scrollTo(0, document.body.scrollHeight);
                            }
                        }
                        if (parseInt(skip) >= parseInt(resp.total)) {
                            $("#loadMore").addClass('d-none');
                        } else {
                            $("#loadMore").removeClass('d-none')
                        }
                    },
                    error: function(e) {
                        notify("undefined")
                    }
                });
            };

            $('.coin-symbol').on('click', function(e) {
                $('.coin-symbol').removeClass('active');
                $(this).addClass('active');
                skip = 0;
                isLoadMore = false;
                URLBuilder();
            });

            $('.buy-sell-tab').on('click', 'button', function(e) {
                console.log(3434);

                const type = $(this).data('type');
                skip = 0;
                isLoadMore = false;
                $('.buy-sell-tab').find(`button`).removeClass('active');
                $(this).addClass('active');

                $('.buy-sell-tab').removeClass('buy sell');
                $('.buy-sell-tab').addClass(type);

                URLBuilder();
            });

            setTimeout(() => {
                $('#ad-list-empty').addClass('d-none');
                $('#ad-list').removeClass('d-none');
            }, 1000);

            $("body").on('input', ".search-inside-drodown", function(e) {
                const searchValue = $(this).val().toUpperCase();
                const searchItems = $(this).closest(".dropdown-menu").find('.searchable-item');

                $.each(searchItems, function(indexInArray, searchItem) {
                    const searchItemText = $(searchItem).find('.text').text().toUpperCase();
                    if (searchItemText.indexOf(searchValue) != -1) {
                        $(searchItem).removeClass('d-none');
                    } else {
                        $(searchItem).addClass('d-none');
                    }
                });
            });

            $(".dropdown-menu").on('click', ".searchable-item", function(e) {

                const text = $(this).find('.text').text();
                const value = $(this).data('value');
                const imageUrl = $(this).find('img').attr('src');
                const isImage = imageUrl ? `<img src="${imageUrl}"/>` : '';
                const isColor = $(this).find('.color').length ? $(this).find('.color')[0].outerHTML : '';


                $(this).closest(".dropdown").find(`.dropdown-selcted-result`).html(`
                    ${isImage || isColor}
                    <span class="f-14 has-value" data-value="${value}">${text}</span>
                `);

                skip = 0;
                isLoadMore = false;
                URLBuilder();
            });

            $('.filter-amount').on('change', function(e) {
                skip = 0;
                isLoadMore = false;
                URLBuilder();
            });

            const paymentMethods = @json($paymentMethods);

            $(".currency-dropdown").on('click', ".searchable-item", function(e) {

                skip = 0;

                const currencyPaymentMethods = [];
                const currency = $(".currency-dropdown").find('.has-value').data('value');

                if (!currency) return;

                paymentMethods.forEach(paymentMethod => {
                    if (paymentMethod.supported_currency.includes(currency)) {
                        currencyPaymentMethods.push(paymentMethod);
                    }
                });

                let html = `<li class="p2p-custom--dropdown-search-item">
                    <div class="search-inner">
                        <button class="search-icon" type="search"> <i class="fas fa-search"></i></button>
                        <input class="search-input form--control search-inside-drodown" placeholder="undefined">
                    </div>
                </li>`;

                if (currencyPaymentMethods.length > 0) {
                    currencyPaymentMethods.forEach(currencyPaymentMethod => {
                        html += `<li class="p2p-custom--dropdown-menu-item searchable-item" data-value="${currencyPaymentMethod.slug}">
                            <div slot="select-item" class="link">
                                <span class="color" style="background-color:#${currencyPaymentMethod.branding_color}"></span>
                                <span class="text">${currencyPaymentMethod.name}</span>
                            </div>
                        </li>`
                    });
                } else {
                    html += `
                    <li class="p2p-custom--dropdown-menu-item searchable-item" >
                        <div slot="select-item" class="link">
                            <span class="text">undefined</span>
                        </div>
                    </li>`
                }
                $(".payment-method-dropdown").find(`.dropdown-menu`).html(html);
            });

            let adPrice, minAmount, maxAmount, addId = 0;

            $('body').on('click', '.trade-request', function(e) {
                @guest()
                    notify('error', "undefined");
                    window.location = "{{ route('user.login') }}";
                    return
                @endguest

                const action = "{{ route('user.p2p.trade.request', ':id') }}";
                const $this = $(this);
                const type = $(this).data('type');

                adPrice = Number($(this).data('price'));
                minAmount = Number($(this).data('min'));
                maxAmount = Number($(this).data('max'));
                addId = $(this).data('id');

                const submit = $(this);
                const oldHmtl = submit.html();

                $.ajax({
                    url: action.replace(":id", addId),
                    method: "GET",
                    data: {
                        type
                    },
                    beforeSend: function() {
                        submit.html(`<div class="spinner-border" role="status"></div>`);
                        submit.attr(`disabled`, true);
                    },
                    complete: function() {
                        submit.html(oldHmtl);
                        submit.attr(`disabled`, false);
                    },
                    success: function(resp) {
                        if (resp.success) {
                            $(`.p2p-table`).find(`tr.d-none`).removeClass(`d-none`);
                            $(`.p2p-table`).find(`tr.data-label-none`).remove();
                            $this.closest('tr').addClass('d-none');
                            $this.closest('tr').after(
                                ` <tr class="data-label-none">
                                <td colspan="100%"> ${resp.data.html}</td>
                            </tr>`
                            );
                        } else {
                            notify('error', resp.message);
                        }

                    },
                    error: function(e) {
                        notify("error", "undefined");
                    }
                });
            });

            $(`body`).on(`click`, `.cancel-trade-request`, function(e) {
                $(this).closest(`tr`).remove();
                $(`.p2p-table`).find(`.trade-request`).attr(`disabled`, false);
                $(`.p2p-table`).find(`tr.d-none`).removeClass(`d-none`);
            });

            $(`body`).on(`input`, '.asset-amount', function(e) {
                $(this).val($(this).val().replace('/^-?\d.*(?<=\d)$/'));
                if (!adPrice) return;

                const amount = Number($(this).val());
                if (amount < 0) {
                    amount = 0;
                    $(this).val(getAmount(0));
                }
                const fiatAmount = adPrice * amount;

                $(`body`).find(`.fiat-amount`).val(getAmount(fiatAmount));

                checkLimit();
            });

            $(`body`).on(`input`, '.fiat-amount', function(e) {

                if (!adPrice) return;

                const amount = Number($(this).val());
                if (amount < 0) {
                    amount = 0;
                    $(this).val(getAmount(0));
                }
                const assetAmount = amount / adPrice;

                $(`body`).find(`.asset-amount`).val(getAmount(assetAmount));

                checkLimit();
            });

            function checkLimit() {
                const fiatAmount = Number($(`body`).find(".fiat-amount").val())
                if ((fiatAmount < minAmount) || (fiatAmount > maxAmount)) {
                    $(`body`).find(`.p2p-button button[type=submit]`).attr('disabled', true);
                } else {
                    $(`body`).find(`.p2p-button button[type=submit]`).removeAttr('disabled');
                }
            }

            $("body").on('submit', ".trade-rquest-form", function(e) {
                e.preventDefault();

                const token = "{{ csrf_token() }}";
                const formData = new FormData($(this)[0]);
                const action = "{{ route('user.p2p.trade.request.save', ':id') }}";
                const submitBtn = $(this).find(`button[type=submit]`);
                const oldHmtl = submitBtn.html();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    url: action.replace(':id', addId),
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        submitBtn.html(`<div class="spinner-border" role="status"></div>`);
                        submitBtn.attr('disabled', true);
                    },
                    complete: function() {
                        submitBtn.html(oldHmtl);
                        submitBtn.attr('disabled', false);
                    },
                    success: function(resp) {
                        if (resp.success) {
                            window.location.href = resp.data.url;
                        } else {
                            if ('data' in resp && 'ad_payment_method' in resp.data) {
                                $("#payment-method-canvas").find(`.offcanvas-title`).text(resp.data
                                    .title);
                                $("#payment-method-canvas").find(`.offcanvas-body`).html(resp.data
                                    .html);
                                new bootstrap.Offcanvas(document.getElementById(
                                    'payment-method-canvas')).show();
                            }
                            notify("error", resp.message);
                        }
                    },
                    error: function(e) {
                        notify("undefined")
                    }
                });
            });

            $("body").on('click', "#loadMore", function(e) {
                isLoadMore = true;

                URLBuilder();

            });

            $("#payment-method-canvas").on('submit', 'form', function(e) {
                e.preventDefault();
                const token = "{{ csrf_token() }}";
                const formData = new FormData($(this)[0]);
                const action = $(this).attr('action');
                const submitBtn = $(this).find(`button[type=submit]`);
                const oldHmtl = submitBtn.html();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    url: action,
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        submitBtn.html(`<div class="spinner-border" role="status"></div>`);
                        submitBtn.attr('disabled', true);
                    },
                    complete: function() {
                        submitBtn.html(oldHmtl);
                        submitBtn.attr('disabled', false);
                    },
                    success: function(resp) {
                        if (resp.success) {
                            $("#payment-method-canvas").find(`[data-bs-dismiss="offcanvas"]`).click();
                            notify("success", resp.message);
                        } else {
                            notify("error", resp.message);
                        }
                    },
                    error: function(e) {
                        notify("undefined")
                    }
                });
            });

        })(jQuery);
    </script>
@endpush
