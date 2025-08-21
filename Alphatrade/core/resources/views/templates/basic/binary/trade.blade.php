@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="trade-section">
        <div class="container-fluid container-fluid--custom">
            <div class="trade-section__inner">
                <div class="trade-section__left">
                    <div id="chart-container">
                        <div id="countdown"></div>
                        <div id="direction-indicator"></div>
                        <div id="current-price-dot"></div>
                    </div>
                </div>
                <div class="trade-section__right">
                    <button type="button" class="btn--close">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="trade-section__block one">
                        <div class="trade-amount">
                            <div class="trade-amount__input-group">
                                <span class="trade-amount__label">@lang('Amount') (<span class="coin-symbol">{{ strstr(@$activeCoin->symbol, '_', true) }}</span>)</span>
                                <div class="trade-amount__wrapper">
                                    <input class="trade-amount__input" name="amount" value="{{ getAmount(@$activeCoin->min_binary_trade_amount) }}" type="text">
                                </div>
                            </div>
                            <div class="trade-amount__btns">
                                <button class="trade-amount__increment" type="button"><i class="fas fa-plus"></i></button>
                                <button class="trade-amount__decrement" type="button"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>

                        <div class="trade-duration">
                            <div class="trade-duration__toggle" data-bs-toggle="dropdown">
                                <span class="label">@lang('Duration')</span>
                                <div class="wrapper">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_19_8761)">
                                            <path d="M9.99984 18.3333C14.6022 18.3333 18.3332 14.6024 18.3332 9.99999C18.3332 5.39762 14.6022 1.66666 9.99984 1.66666C5.39746 1.66666 1.6665 5.39762 1.6665 9.99999C1.6665 14.6024 5.39746 18.3333 9.99984 18.3333Z" stroke="#CBD5E1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M10 5V10H13.75" stroke="#CBD5E1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_19_8761">
                                                <rect width="20" height="20" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    <span class="value timer-value">@php echo  convertToMinutesSeconds($durations[0]) @endphp</span>
                                </div>
                            </div>
                            <div class="dropdown-menu">
                                <ul class="trade-duration-presets">
                                    @foreach ($durations ?? [] as $duration)
                                        <li class="trade-duration-presets__item">@php echo  convertToMinutesSeconds($duration) @endphp</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="trade-section__block two">
                        <div class="percentage-box">
                            <span class="percentage-box__title">@lang('Profit')</span>
                            <div class="percentage-box__wrapper">
                                <h1 class="percentage-box__total">+{{ getAmount(@$activeCoin->binary_trade_profit) }}%</h1>
                                <h6 class="percentage-box__amount"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="trade-section__block three">
                        <button type="button" class="btn-new btn-new--success w-100" id="higherBtn" data-direction="higher">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.3335 9.33333H22.6668V22.6667" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M9.3335 22.6667L22.6668 9.33333" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>@lang('Higher')</span>
                        </button>
                        <button type="button" class="btn-new btn-new--danger w-100" id="lowerBtn" data-direction="lower">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.3335 22.6667H22.6668V9.33334" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M9.3335 9.33334L22.6668 22.6667" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>@lang('Lower')</span>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </section>

    @auth
        <div class="terminal">
            <div class="terminal-header">
                <div class="container-fluid container-fluid--custom">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="terminal-title">@lang('History')</h6>
                        <button class="terminal-toggle" type="button"><span>@lang('Show History')</span>
                            <i class="las la-angle-up"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="terminal-body">
                <div class="container-fluid container-fluid--custom">
                    <ul class="nav nav-tabs custom--tab-new" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#running-tab" type="button" role="tab">@lang('Running')</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#closed-tab" type="button" role="tab">@lang('Closed')</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="running-tab" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                            <div class="table-wrapper">
                                <table class="table table-new2" id="runningTradeTable">
                                    @include('Template::partials.binary_table', ['trades' => $runningTrades])
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="closed-tab" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                            <div class="table-wrapper">
                                <table class="table table-new2" id="closedTradeTable">
                                    @include('Template::partials.binary_table', ['trades' => $closedTrades])
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/lightweight-chart.js') }}"></script>
@endpush

@push('style')
    <style>
        body {
            background-color: hsl(var(--gray-d-500));
        }

        #chart-container {
            width: 100%;
            background-color: hsl(var(--footer-bg));
            position: relative;
            overflow: hidden;
            border-radius: 8px
        }

        #trading-controls {
            background-color: #2a2e39;
            border-radius: 10px;
            padding: 20px;
            height: 100%;
        }

        .btn-custom {
            background-color: #4caf50;
            color: white;
            width: 100%;
        }

        .form-control,
        .form-select {
            background-color: #1e222d;
            border: 1px solid #363c4e;
            color: #d1d4dc;
            margin-bottom: 15px;
        }

        #countdown {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 24px;
            z-index: 1000;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        #direction-indicator {
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 1000;
            font-size: 14px;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
            pointer-events: none;
            display: none;
        }

        #current-price-dot {
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #35c75a;
            pointer-events: none;
            animation: pulse 0.1s infinite;
        }

        #binaryTable tbody,
        tr {
            cursor: pointer;
        }

        .empty-thumb {
            padding: 0 !important;
            min-height: 145px !important;
        }

        .table tbody tr td:nth-last-child(3) {
            color: hsl(var(--white) / 0.7);
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            let countdownTimer = $('.timer-value').eq(0);
            $(document).on('click', '.trade-duration-presets__item', function(e) {
                let durationText = $(this).text();
                countdownTimer.text(durationText);
            });

            let coinPairId = Number(`{{ @$activeCoin->id }}`);
            let activeCoin = `{{ @$activeCoin->symbol }}`;
            let coinSymbol = `{{ strstr(@$activeCoin->symbol, '_', true) }}`;
            let profitPercentage = Number(`{{ getAmount(@$activeCoin->binary_trade_profit) }}`);
            let coinImage = `{{ getImage(getFilePath('currency') . '/' . @$activeCoin->coin->image, getFileSize('currency')) }}`;
            let marketImage = `{{ getImage(getFilePath('currency') . '/' . @$activeCoin->market->currency->image, getFileSize('currency')) }}`
            let BINANCE_API_URL;
            let BINANCE_WEBSOCKET_URL;
            let chart = null;
            let lineSeries = null;
            let areaSeries = null;
            let lastPrice = 0;
            let investmentPriceLine = null;
            let webSocket = null;
            let chartWidth = Math.ceil($(".trade-section__left").width());
            let chartHeight = Math.ceil($(".trade-section__left").height());
            let chartProperties = null;
            let direction;
            let dataIds = [];
            let isTradeRunning = false;
            let incrementAmount = Number("{{ @$activeCoin->binary_increment_amount }}");
            let minTradeAmount = Number("{{ @$activeCoin->min_binary_trade_amount }}");
            let maxTradeAmount = Number("{{ @$activeCoin->max_binary_trade_amount }}");

            $(document).on('click', '.asset-compact-card__close', function(e) {
                e.stopPropagation();
                if (isTradeRunning) {
                    return;
                }
                let closeElement = $(this);
                coinPairId = $('#show-currency-list').find('li:first').find('.coinBtn').data('id');
                let thisCoinPairId = closeElement.parent('.coinBtn').data('id');
                let url = `{{ route('binary.trade.tab.close') }}/${thisCoinPairId}/${coinPairId}`;
                if (thisCoinPairId != coinPairId) {
                    showLoading();
                    $.get(url)
                        .done(function(response) {
                            updatePageData(response);
                            $('#show-currency-list').find('li .asset-compact-card').removeClass('active');
                            $('#show-currency-list').find('li:first').find('.asset-compact-card').addClass('active');
                            closeElement.closest('li').remove();
                        })
                        .fail(function(xhr, status, error) {
                            notify('error', 'Something went wrong');
                        })
                        .always(function() {
                            hideLoading();
                        });
                }
            });

            $('.dropdown-slider__slide .coinBtn, tbody .coinBtn').on('click', function(e) {
                if (isTradeRunning) {
                    return;
                }
                let thisCoinPairId = $(this).data('id');
                let topBarCoinPairId = [];
                $.each($("#show-currency-list li"), function(index, item) {
                    topBarCoinPairId.push($(item).find('.coinBtn').data('id'));
                });

                showLoading();
                if (topBarCoinPairId.includes(thisCoinPairId)) {
                    let url = `{{ route('binary.trade.tab.update') }}/${thisCoinPairId}`;
                    $.get(url)
                        .done(function(response) {
                            $('#show-currency-list').find('li .coinBtn').removeClass('active');
                            $(`#show-currency-list li .coinBtn[data-id='${thisCoinPairId}']`).addClass('active');
                            updatePageData(response);
                            $('.assets--dropdown .dropdown-menu').removeClass('show')
                        })
                        .fail(function(xhr, status, error) {
                            notify('error', 'Something went wrong');
                            $('.assets--dropdown .dropdown-menu').removeClass('show')
                        })
                        .always(function() {
                            hideLoading();
                        });
                } else {
                    let url = `{{ route('binary.trade.tab.add') }}/${thisCoinPairId}`;
                    $.get(url)
                        .done(function(response) {
                            let showCurrencyList = $("#show-currency-list");
                            if (showCurrencyList.find('li').length == 6) {
                                showCurrencyList.find('li:last').remove();
                            }
                            let currencyHtml = `<li class="nav-horizontal-menu__item">
                                                    <div class="asset-compact-card coinBtn active" data-id="${response.activeCoin.id}">
                                                            <div class="avatar">
                                                                <img class="avatar-img" src="${response.activeCoin.coin.image_url}" alt="img">
                                                                <img class="avatar-img" src="${response.activeCoin.market.currency.image_url}" alt="img">
                                                            </div>
                                                            <div class="asset-compact-card__content">
                                                                <h6 class="asset-compact-card__title">${response.activeCoin.symbol.replace('_','/')}</h6>
                                                                <span class="asset-compact-card__percentage">${Number(response.activeCoin.binary_trade_profit)}%</span>
                                                                </div>

                                                                <button class="asset-compact-card__close" type="button">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </li>`;
                            $('#show-currency-list').find('li .asset-compact-card').removeClass('active');
                            showCurrencyList.append(currencyHtml);
                            updatePageData(response);
                            $('.assets--dropdown .dropdown-menu').removeClass('show')
                        })
                        .fail(function(xhr, status, error) {
                            $('.assets--dropdown .dropdown-menu').removeClass('show')
                            notify('error', 'Something went wrong');
                        })
                        .always(function() {
                            hideLoading();
                        });
                }

            });

            function showLoading() {
                $('body').append(`<div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999;">
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                        <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div style="color: white; margin-top: 1rem; font-size: 1.1rem;">Loading...</div>
                    </div>
                </div>`);
            }

            function hideLoading() {
                $('#loading-overlay').remove();
            }

            $(document).on('click', '.nav-horizontal-menu__item .coinBtn', function(e) {
                e.stopPropagation();
                if (isTradeRunning) {
                    return;
                }
                let clickedCoin = $(this);
                coinPairId = clickedCoin.data('id');
                let url = `{{ route('binary.trade.tab.update') }}/${coinPairId}`;
                showLoading();
                $.get(url, function(response) {
                        $('#show-currency-list').find('li .coinBtn').removeClass('active');
                        clickedCoin.addClass('active');
                        updatePageData(response);
                    })
                    .fail(function(xhr, status, error) {
                        notify('error', 'Something went wrong');
                    })
                    .always(function() {
                        hideLoading();
                    });
            });

            function updatePageData(response) {
                cleanupChart();
                activeCoin = response.activeCoin.symbol;
                incrementAmount = response.activeCoin.binary_increment_amount;
                initalizeApi(activeCoin);
                initializeChart();
                chartPropertiesFunc(Math.ceil($(".trade-section__left").height()))
                coinSymbol = activeCoin.substring(0, activeCoin.indexOf('_'));
                $('.coin-symbol').text(coinSymbol);
                minTradeAmount = Number(response.activeCoin.min_binary_trade_amount);
                maxTradeAmount = Number(response.activeCoin.max_binary_trade_amount);
                minInvest = minTradeAmount;
                maxInvest = maxTradeAmount;

                $('[name=amount]').val(minTradeAmount)
                $('.timer-value').text(response.firstDuration)
                $('.trade-duration-presets').html(response.durations)
                $('.percentage-box__total').text(`+${Number(response.activeCoin.binary_trade_profit)}%`)
                profitPercentage = response.activeCoin.binary_trade_profit
                getProfit();
            }


            chartPropertiesFunc(chartHeight)

            function chartPropertiesFunc(height) {
                chartProperties = {
                    width: chartWidth,
                    height: height,
                    timeScale: {
                        timeVisible: true,
                        secondsVisible: false,
                        rightOffset: 100,
                        barSpacing: 5,
                        borderColor: '#363C4E',
                        tickMarkFormatter: (time) => {
                            const date = new Date(time * 1000);
                            return date.toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false,
                            });
                        },
                    },
                    rightPriceScale: {
                        borderColor: '#363C4E',
                    },
                    layout: {
                        background: {
                            type: 'solid',
                            color: '#131722'
                        },
                        textColor: '#D1D4DC',
                    },
                    grid: {
                        vertLines: {
                            color: '#1E222D'
                        },
                        horzLines: {
                            color: '#1E222D'
                        },
                    },
                    crosshair: {
                        mode: LightweightCharts.CrosshairMode.Normal,
                        vertLine: {
                            width: 1,
                            color: `#{{ gs('base_color') }}`,
                            style: LightweightCharts.LineStyle.Dashed,
                            labelBackgroundColor: `#{{ gs('base_color') }}`,
                        },
                        horzLine: {
                            width: 1,
                            color: `#{{ gs('base_color') }}`,
                            style: LightweightCharts.LineStyle.Dashed,
                            labelBackgroundColor: `#{{ gs('base_color') }}`,
                        },
                    },
                    handleScale: {
                        mouseWheel: true,
                        pinch: true,
                        axisPressedMouseMove: true,
                    },
                    handleScroll: {
                        mouseWheel: true,
                        pressedMouseMove: true,
                        horzTouchDrag: true,
                        vertTouchDrag: true,
                    }
                };
            }

            initalizeApi(activeCoin);

            function initalizeApi(activeCoin) {
                let symbol = activeCoin.replace('_', '');
                BINANCE_API_URL = `https://api.binance.com/api/v3/klines?symbol=${symbol.toUpperCase()}&interval=1s&limit=2000`;
                BINANCE_WEBSOCKET_URL = `wss://stream.binance.com:9443/ws/${symbol.toLowerCase()}@kline_1s`;
            }

            function cleanupChart() {
                if (webSocket) {
                    webSocket.close();
                    webSocket = null;
                }
                if (chart) {
                    if (lineSeries) {
                        chart.removeSeries(lineSeries);
                        lineSeries = null;
                    }
                    if (areaSeries) {
                        chart.removeSeries(areaSeries);
                        areaSeries = null;
                    }

                    chart.remove();
                    chart = null;

                    const container = document.getElementById('chart-container');
                    container.innerHTML = `
                                            <div id="countdown"></div>
                                            <div id="direction-indicator"></div>
                                            <div id="current-price-dot"></div>
                                        `;
                }
            }

            function initializeChart() {

                chart = LightweightCharts.createChart(
                    document.getElementById('chart-container'),
                    chartProperties
                );

                lineSeries = chart.addLineSeries({
                    color: '#02d428',
                    lineWidth: 2,
                    lastPriceAnimation: LightweightCharts.LastPriceAnimationMode.OnDataUpdate,
                    lastValueVisible: false,
                    priceLineVisible: false,
                });

                areaSeries = chart.addAreaSeries({
                    topColor: 'rgba(33, 150, 243, 0.56)',
                    bottomColor: 'rgba(33, 150, 243, 0.04)',
                    lineColor: `#{{ gs('base_color') }}`,
                    lineWidth: 2,
                });

                const tvLogo = document.querySelector('#tv-attr-logo');
                if (tvLogo) {
                    tvLogo.style.display = 'none';
                }
                loadHistoricalData();
                initializeWebSocket();
                setupDirectionIndicators()

            }

            async function loadHistoricalData() {
                try {
                    const response = await fetch(BINANCE_API_URL);
                    const data = await response.json();
                    const chartData = data.map(d => ({
                        time: d[0] / 1000,
                        value: parseFloat(d[4])
                    }));

                    const uniqueChartData = chartData.filter((v, i, a) => a.findIndex(t => (t.time === v.time)) === i);

                    lineSeries.setData(uniqueChartData);
                    areaSeries.setData(uniqueChartData);
                    lastPrice = uniqueChartData[uniqueChartData.length - 1].value;
                    chart.timeScale().fitContent();

                } catch (error) {
                    console.error('Error loading historical data:', error);
                }
            }

            function initializeWebSocket() {
                webSocket = new WebSocket(BINANCE_WEBSOCKET_URL);
                webSocket.onmessage = handleWebSocketMessage;
            }

            function handleWebSocketMessage(event) {
                const message = JSON.parse(event.data);
                const candlestick = message.k;

                lastPrice = parseFloat(candlestick.c);

                const newData = {
                    time: candlestick.t / 1000,
                    value: lastPrice,
                };
                updateChartData(newData);
                updatePriceDot(lastPrice);
            }

            function updateChartData(newData) {
                lineSeries.update(newData);
                areaSeries.update(newData);

                if (investmentPriceLine) {
                    updateInvestmentLine();
                }
            }

            function updateInvestmentLine() {

                var color = direction == 'higher' ? 'green' : 'red';
                lineSeries.removePriceLine(investmentPriceLine);
                investmentPriceLine = lineSeries.createPriceLine({
                    price: investmentPriceLine.options().price,
                    color: color,
                    lineWidth: 2,
                    lineStyle: LightweightCharts.LineStyle.Dashed,
                    axisLabelVisible: true,
                    title: investmentPriceLine.options().title,
                });
            }

            function updatePriceDot(price) {

                const dot = document.getElementById('current-price-dot');
                if (dot) {
                    const y = lineSeries.priceToCoordinate(price);
                    const x = chart.timeScale().width() - 5;
                    dot.style.top = `${y}px`;
                    dot.style.left = `${x}px`;
                }
            }

            function validateTradeInput(direction, amount, duration) {
                return direction &&
                    !isNaN(amount) &&
                    amount > 0 &&
                    !isNaN(duration) &&
                    duration > 0;
            }

            function createInvestmentLine(amount) {
                var color = direction == 'higher' ? 'green' : 'red';
                investmentPriceLine = lineSeries.createPriceLine({
                    price: lastPrice,
                    color: color,
                    lineWidth: 2,
                    lineStyle: LightweightCharts.LineStyle.Dashed,
                    axisLabelVisible: true,
                    title: `${amount} ${coinSymbol}`,
                });
            }

            function startTradeCountdown(duration) {
                const countdownElement = document.querySelector('.timer-value');
                let remainingTime = duration;
                const countdownInterval = setInterval(() => {
                    remainingTime--;
                    const minutes = Math.floor(remainingTime / 60);
                    const seconds = remainingTime % 60;
                    countdownElement.textContent =
                        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                    if (remainingTime <= 0) {
                        clearInterval(countdownInterval);
                        countdownElement.textContent = $('.trade-duration-presets li:first').text();
                    }
                }, 1000);
            }

            function scheduleTradeCompletion(duration, binaryTradeId) {
                setTimeout(() => {
                    const data = {
                        '_token': "{{ csrf_token() }}",
                        'binary_trade_id': binaryTradeId
                    };
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.binary.trade.complete') }}",
                        data: data,
                        success: function(response) {
                            lineSeries.removePriceLine(investmentPriceLine);
                            investmentPriceLine = null;

                            $("#higherBtn").prop('disabled', false);
                            $("#lowerBtn").prop('disabled', false);

                            if (response.error) {
                                isTradeRunning = false;
                                notify('error', response.error);
                                return;
                            }

                            if (response.win_status == 2) {
                                notify('error', response.notification);
                            } else {
                                notify('success', response.notification);
                            }
                            isTradeRunning = false;

                            $('#runningTradeTable tbody').find('tr').remove();

                            $('#runningTradeTable tbody').html(`
                            <tr>
                                <td class="text-muted text-center" colspan="100%">
                                    <div class="empty-thumb text-center p-5">
                                        <img src="{{ getImage('assets/images/extra_images/empty.png') }}"/>
                                        <p class="fs-14">No trade found</p>
                                    </div>
                                </td>
                            </tr>`);

                            $("#closedTradeTable").html(response.closedTradeTable)
                        }
                    });
                }, duration * 1000);
            }

            function setupDirectionIndicators() {
                const directionIndicator = document.getElementById('direction-indicator');
                const higherOption = document.getElementById('higherBtn');
                const lowerOption = document.getElementById('lowerBtn');

                if (!directionIndicator || !higherOption || !lowerOption) return;

                setupDirectionHoverEffects(directionIndicator, higherOption, lowerOption);
                setupCrosshairMove(directionIndicator);
            }

            function setupDirectionHoverEffects(indicator, higherBtn, lowerBtn) {
                higherBtn.addEventListener('mouseover', () => {
                    const currentPriceY = lineSeries.priceToCoordinate(lastPrice);
                    indicator.style.background =
                        'linear-gradient(0deg, rgba(26,205,35,0.09287464985994398) 40%, rgba(193,193,193,0) 93%)';
                    indicator.style.height = `${currentPriceY}px`;
                    indicator.style.top = '0';
                    indicator.style.left = '0';
                    indicator.style.width = '100%';
                    indicator.style.display = 'block';
                    showDirectionText('UP', 'green');
                });

                higherBtn.addEventListener('mouseout', () => {
                    indicator.style.backgroundColor = '';
                    indicator.style.height = 'auto';
                    indicator.style.width = 'auto';
                    indicator.style.display = 'none';
                    hideDirectionText();
                });

                lowerBtn.addEventListener('mouseover', () => {
                    const currentPriceY = lineSeries.priceToCoordinate(lastPrice);
                    indicator.style.background =
                        'linear-gradient(180deg, rgba(205,26,53,0.14329481792717091) 40%, rgba(193,193,193,0) 93%)';
                    indicator.style.height = `calc(100% - ${currentPriceY}px)`;
                    indicator.style.top = `${currentPriceY}px`;
                    indicator.style.left = '0';
                    indicator.style.width = '100%';
                    indicator.style.display = 'block';
                    showDirectionText('DOWN', 'red');
                });

                lowerBtn.addEventListener('mouseout', () => {
                    indicator.style.backgroundColor = '';
                    indicator.style.height = 'auto';
                    indicator.style.width = 'auto';
                    indicator.style.display = 'none';
                    hideDirectionText();

                });
            }

            function showDirectionText(text, color) {
                let textElement = document.getElementById('direction-text');
                if (!textElement) {
                    textElement = document.createElement('div');
                    textElement.id = 'direction-text';
                    document.getElementById('chart-container').appendChild(textElement);
                }

                const arrowIcon = text === 'UP' ?
                    '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M4 20L20 4M20 4H10M20 4V14" stroke="currentColor" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/></svg>' :
                    '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M4 4L20 20M20 20V10M20 20H10" stroke="currentColor" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/></svg>';

                textElement.innerHTML = `<span style="font-size: 16px;">${arrowIcon}</span>`;
                textElement.style.color = color;
                textElement.style.position = 'absolute';
                textElement.style.zIndex = '1000';
                textElement.style.fontWeight = 'bold';
                textElement.style.fontSize = '16px';
                textElement.style.display = 'flex';

                function updatePosition() {
                    if (lineSeries && chart && lastPrice !== undefined) {
                        const y = lineSeries.priceToCoordinate(lastPrice);
                        const x = chart.timeScale().width();
                        if (y !== null && x !== null) {
                            textElement.style.left = `${x - 60}px`;
                            textElement.style.top = text === 'UP' ? `${y - 50}px` : `${y + 10}px`;
                        }
                    }
                }

                updatePosition();
                if (chart) {
                    chart.subscribeCrosshairMove(updatePosition);
                }
            }

            function hideDirectionText() {
                const textElement = document.getElementById('direction-text');
                if (textElement) {
                    textElement.style.display = 'none';
                }
            }

            function setupCrosshairMove(indicator) {
                chart.subscribeCrosshairMove((param) => {
                    if (param.point && param.seriesPrices?.get(lineSeries)) {
                        const price = param.seriesPrices.get(lineSeries);

                        const currentPriceY = lineSeries.priceToCoordinate(lastPrice);

                        if (price !== undefined) {
                            indicator.style.left = `${param.point.x}px`;
                            const higherOption = document.getElementById('higherBtn');
                            const lowerOption = document.getElementById('lowerBtn');

                            if (higherOption?.checked) {
                                indicator.style.top = `${currentPriceY - 30}px`;
                            } else if (lowerOption?.checked) {
                                indicator.style.top = `${currentPriceY + 10}px`;
                            }
                        }
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', initializeChart);
            let higherBtn = $('#higherBtn');
            let lowerBtn = $('#lowerBtn');
            let minInvest = minTradeAmount;
            let maxInvest = maxTradeAmount;
            let auth = "{{ auth()->check() }}";

            higherBtn.on('click', function() {
                placeBinaryTrade($(this))
            });

            lowerBtn.on('click', function() {
                placeBinaryTrade($(this))
            });

            function placeBinaryTrade(directionBtn) {
                if (!auth) {
                    notify('error', 'You must be logged in to place a trade')
                    return;
                }
                let amount = $('[name=amount]').val();
                if (!amount) {
                    notify('error', 'Please enter an amount to invest')
                    return;
                }
                if (amount < minInvest || amount > maxInvest) {
                    notify('error', `Investment amount must be between ${minInvest} and ${maxInvest}`)
                    return;
                }
                let duration = convertToSeconds(countdownTimer.text());

                direction = directionBtn.data('direction');
                if (!direction) {
                    notify('error', 'Please select a direction for the trade')
                    return;
                }

                let directions = ['higher', 'lower'];
                if (!directions.includes(direction)) {
                    notify('error', 'Invalid direction for the trade')
                    return;
                }

                let data = {
                    '_token': "{{ csrf_token() }}",
                    'amount': amount,
                    'duration': duration,
                    'direction': direction,
                    'coin_pair_id': coinPairId
                }

                if (investmentPriceLine) {
                    lineSeries.removePriceLine(investmentPriceLine);
                }

                $("#higherBtn").prop('disabled', true);
                $("#lowerBtn").prop('disabled', true);
                isTradeRunning = true;

                $.ajax({
                    type: "POST",
                    url: "{{ route('user.binary.trade.order') }}",
                    data: data,
                    success: function(response) {
                        if (response.error) {
                            isTradeRunning = false;
                            $("#higherBtn").prop('disabled', false);
                            $("#lowerBtn").prop('disabled', false);
                            notify('error', response.error);
                            return;
                        }
                        $('#runningTradeTable tbody').find('tr').remove();
                        $('#runningTradeTable tbody').prepend(response.newTrade);
                        createInvestmentLine(response.amount, response.direction);
                        startTradeCountdown(response.duration);
                        scheduleTradeCompletion(response.duration, response.binary_trade_id);
                    }
                });
            }

            function convertToSeconds(time) {
                let parts = time.split(":");
                let minutes = parseInt(parts[0], 10);
                let seconds = parseInt(parts[1], 10);
                return (minutes * 60) + seconds;
            }

            $('[name=amount]').on('input', function(e) {
                getProfit();
            });

            $('.trade-amount__increment').on('click', function(e) {
                getProfit();
            });
            $('.trade-amount__decrement').on('click', function(e) {
                getProfit();
            });

            function getProfit() {
                let investAmount = Number($('[name=amount]').val());
                let totalProfit = investAmount + (investAmount * profitPercentage / 100);
                $('.percentage-box__amount').text(`+${parseFloat(totalProfit).toFixed(Number("{{ gs('allow_decimal_after_number') }}"))}`)
            }

            getProfit()

            /* ==================== Terminal  JS Startaz ================================= */
            let terminal = $(".terminal");
            let terminalToggle = terminal.find(".terminal-toggle");
            let terminalBody = terminal.find(".terminal-body");
            let terminalBodyTabContent = terminalBody.find(".tab-content");

            terminalBodyTabContent.on('scroll', function() {
                if ($(this).scrollTop() > 0) {
                    $(this).addClass('scrolling');
                } else {
                    $(this).removeClass('scrolling');
                }
            });

            terminalToggle.on("click", function() {
                $(terminalBody).toggle();

                if ($(terminalBody).css("display") != "none") {
                    $(this).find("span").text("Hide History");
                    $(this).find("i").removeClass("la-angle-up").addClass("la-angle-down");

                    let tradeLeftNewHeight = $('.trade-section__left').height() - $(terminalBody).height();
                    $('.trade-section__left').height(tradeLeftNewHeight);

                    chartPropertiesFunc(tradeLeftNewHeight);
                    cleanupChart();
                    initializeChart();


                } else {
                    $('.trade-section__left').removeAttr('style');
                    $(this).find("span").text("Show History");
                    $(this).find("i").removeClass("la-angle-down").addClass("la-angle-up");


                    chartPropertiesFunc($('.trade-section__left').height());
                    cleanupChart();
                    initializeChart();
                }
            });
            /* ==================== Terminal  JS End ==================================== */

            /* ==================== Assets Dropdown Slider JS Start ===================== */
            $('.assets--dropdown .dropdown-menu').on('click', function(e) {
                e.stopPropagation(); // Prevents the dropdown from closing
            });

            $('.assets--dropdown').each((index, dropdown) => {
                let toggle = $(dropdown).find('.dropdown-toggle');
                let sliders = $(dropdown).find('.dropdown-slider')
                let menuBody = $(dropdown).find('.dropdown-menu__body')

                menuBody[0].scrollTop = 0;

                menuBody.on('scroll', function() {
                    if ($(this).scrollTop() > 0) {
                        $(this).addClass('scrolling');
                    } else {
                        $(this).removeClass('scrolling');
                    }
                });

                toggle.on('show.bs.dropdown', function() {
                    menuBody[0].scrollTop = 0;

                    sliders.each((index, slider) => {
                        if (!$(slider).hasClass('slick-initialized')) {
                            $(slider).slick({
                                slidesToShow: 3,
                                slidesToScroll: 1,
                                infinite: false,
                                arrows: true,
                                prevArrow: '<button type="button" class="slick-prev"><i class="las la-angle-left"></i></button>',
                                nextArrow: '<button type="button" class="slick-next"><i class="las la-angle-right"></i></button>',
                                responsive: [{
                                        breakpoint: 992,
                                        settings: {
                                            slidesToShow: 2
                                        }
                                    },
                                    {
                                        breakpoint: 425,
                                        settings: {
                                            slidesToShow: 1
                                        }
                                    }
                                ]
                            });
                        }
                    })
                });
            });

            $(".trade-amount").each(function() {
                var amountIncrement = $(this).find(".trade-amount__increment");
                var amountDecrement = $(this).find(".trade-amount__decrement");
                var amountInput = $(this).find(".trade-amount__input");

                amountIncrement.on("click", function() {
                    var oldValue = parseFloat(amountInput.val());
                    var newVal = oldValue + Number(incrementAmount);
                    amountInput.val(parseFloat(newVal).toFixed(Number("{{ gs('allow_decimal_after_number') }}"))).trigger("change");
                });

                amountDecrement.on("click", function() {
                    var oldValue = parseFloat(amountInput.val());
                    if (oldValue <= minTradeAmount) {
                        var newVal = oldValue;
                    } else {
                        var newVal = oldValue - Number(incrementAmount);
                    }
                    amountInput.val(parseFloat(newVal).toFixed(Number("{{ gs('allow_decimal_after_number') }}"))).trigger("change");
                });
            });



            let page = 1;
            let isLoading = false;

            $('.terminal-body .tab-content').on('scroll', function() {
                let div = $(this).get(0);
                if (div.scrollTop + div.clientHeight >= div.scrollHeight - 20) {
                    if (!isLoading) {
                        isLoading = true;
                        page++;
                        $.ajax({
                            url: "{{ route('user.binary.trade.history') }}",
                            method: "GET",
                            data: {
                                page: page
                            },
                            success: function(response) {
                                $('.loading-spinner').remove();
                                if (response.trades.length > 0) {
                                    $('#closedTradeTable tbody').append(response.trades);
                                }
                                isLoading = false;
                            },
                            error: function() {
                                $('.loading-spinner').remove();
                                isLoading = false;
                            }
                        });
                    }
                }
            });
        })(jQuery)
    </script>
@endpush
