export const listAds = `SELECT * FROM p2p_ads ORDER BY id DESC LIMIT ? OFFSET ?`;

export const insertAd = `INSERT INTO p2p_ads (type, user_id, asset_id, fiat_id, payment_window_id, price_type, price, price_margin, minimum_amount, maximum_amount, payment_details, terms_of_trade, auto_replay_text, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`;

export const updateAd = `UPDATE p2p_ads SET type = ?, user_id = ?, asset_id = ?, fiat_id = ?, payment_window_id = ?, price_type = ?, price = ?, price_margin = ?, minimum_amount = ?, maximum_amount = ?, payment_details = ?, terms_of_trade = ?, auto_replay_text = ?, status = ? WHERE id = ?`;

export const toggleAd = `UPDATE p2p_ads SET status = IF(status = 1, 0, 1) WHERE id = ?`;

export const listTrades = `SELECT * FROM p2p_trades ORDER BY id DESC LIMIT ? OFFSET ?`;

export const getTrade = `SELECT * FROM p2p_trades WHERE id = ?`;

export const completeTrade = `UPDATE p2p_trades SET status = 1 WHERE id = ?`;

export const insertTradeMessage = `INSERT INTO p2p_trade_messages (trade_id, admin_id, message) VALUES (?, ?, ?)`;

export const resolveDispute = `UPDATE p2p_trades SET status = 1 WHERE id = ?`;
