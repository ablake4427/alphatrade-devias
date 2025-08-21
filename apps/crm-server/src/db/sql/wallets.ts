export const userWallets = `
  SELECT id, currency, balance
  FROM wallets
  WHERE user_id = ?
`;
