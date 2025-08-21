<?php

namespace App\Constants;

class Status
{

    const ENABLE  = 1;
    const DISABLE = 0;

    const YES = 1;
    const NO  = 0;

    const VERIFIED   = 1;
    const UNVERIFIED = 0;

    const PAYMENT_INITIATE = 0;
    const PAYMENT_SUCCESS  = 1;
    const PAYMENT_PENDING  = 2;
    const PAYMENT_REJECT   = 3;

    const TICKET_OPEN   = 0;
    const TICKET_ANSWER = 1;
    const TICKET_REPLY  = 2;
    const TICKET_CLOSE  = 3;

    const PRIORITY_LOW    = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH   = 3;

    const USER_ACTIVE = 1;
    const USER_BAN    = 0;

    const KYC_UNVERIFIED = 0;
    const KYC_PENDING    = 2;
    const KYC_VERIFIED   = 1;

    const GOOGLE_PAY = 5001;

    const CUR_BOTH = 1;
    const CUR_TEXT = 2;
    const CUR_SYM  = 3;

    const CRYPTO_CURRENCY = 1;
    const FIAT_CURRENCY   = 2;

    const BUY_SIDE_ORDER  = 1;
    const SELL_SIDE_ORDER = 2;

    const BUY_SIDE_TRADE  = 1;
    const SELL_SIDE_TRADE = 2;

    const ORDER_TYPE_LIMIT      = 1;
    const ORDER_TYPE_MARKET     = 2;
    const ORDER_TYPE_STOP_LIMIT = 3;

    const ORDER_OPEN       = 0;
    const ORDER_COMPLETED  = 1;
    const ORDER_PENDING    = 2;
    const ORDER_POSITIONED = 3;
    const ORDER_CANCELED   = 9;

    const WALLET_TYPE_SPOT    = 1;
    const WALLET_TYPE_FUNDING = 2;
    const WALLET_TYPE_FUTURE  = 3;

    const P2P_AD_PENDING   = 0;
    const P2P_AD_COMPLETED = 1;
    const P2P_AD_REJECT    = 9;

    const P2P_AD_PRICE_TYPE_FIXED  = 1;
    const P2P_AD_PRICE_TYPE_MARGIN = 2;

    const P2P_AD_TYPE_BUY  = 1;
    const P2P_AD_TYPE_SELL = 2;

    const P2P_TRADE_SIDE_BUY  = 1;
    const P2P_TRADE_SIDE_SELL = 2;

    const P2P_TRADE_PENDING   = 0;
    const P2P_TRADE_COMPLETED = 1;
    const P2P_TRADE_PAID      = 2;
    const P2P_TRADE_REPORTED  = 4;
    const P2P_TRADE_CANCELED  = 9;

    const P2P_TRADE_FEEDBACK_POSITIVE = 1;
    const P2P_TRADE_FEEDBACK_NEGATIVE = 0;

    const BINARY_TRADE_PENDING    = 0;
    const BINARY_TRADE_WIN        = 1;
    const BINARY_TRADE_LOSE       = 2;
    const BINARY_TRADE_REFUND     = 3;
    const BINARY_TRADE_PROCESSING = 4;

    const SPOT_TRADE   = 1;
    const BINARY_TRADE = 2;
    const BOTH_TRADE   = 3;

    const MARGIN_MODE_CROSS    = 1;
    const MARGIN_MODE_ISOLATED = 2;

    const QUEUE_MARKET_ORDER     = 1;
    const QUEUE_LIMIT_ORDER      = 2;
    const QUEUE_STOP_LIMIT       = 3;
    const QUEUE_TAKE_PROFIT_LOSS = 4;

    const FUTURE_QUEUE_OPEN   = 0;
    const FUTURE_QUEUE_COMPLETED = 1;
    const FUTURE_QUEUE_CANCELED  = 2;

    const FUTURE_ORDER_POSITIONED = 1;
    const FUTURE_ORDER_CLOSED     = 2;
    const FUTURE_ORDER_LIQUIDATED = 3;
}
