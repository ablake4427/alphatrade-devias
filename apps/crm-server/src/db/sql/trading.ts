export const listSpotOrders = `
  SELECT * FROM spot_orders
  ORDER BY id DESC
  LIMIT ? OFFSET ?
`;

export const cancelSpotOrder = `
  DELETE FROM spot_orders WHERE id = ?
`;

export const listSpotTrades = `
  SELECT * FROM spot_trades
  ORDER BY id DESC
  LIMIT ? OFFSET ?
`;

export const listFuturesPositions = `
  SELECT * FROM futures_positions
  ORDER BY id DESC
  LIMIT ? OFFSET ?
`;

export const listFuturesOrders = `
  SELECT * FROM futures_orders
  ORDER BY id DESC
  LIMIT ? OFFSET ?
`;

export const listBinaryTrades = `
  SELECT * FROM binary_trades
  WHERE (? IS NULL OR status = ?)
  ORDER BY id DESC
  LIMIT ? OFFSET ?
`;

export const refundBinaryTrade = `
  UPDATE binary_trades
  SET status = 'refunded'
  WHERE id = ?
`;
