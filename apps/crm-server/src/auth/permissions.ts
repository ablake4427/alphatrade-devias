export type Action =
  | 'dashboard.read'
  | 'users.read'
  | 'users.write'
  | 'wallets.read'
  | 'deposits.read'
  | 'deposits.write'
  | 'withdrawals.read'
  | 'withdrawals.write'
  | 'spot.orders.read'
  | 'spot.orders.write'
  | 'spot.trades.read'
  | 'futures.positions.read'
  | 'futures.orders.read'
  | 'binary.trades.read'
  | 'binary.trades.write';

export const rolePermissions: Record<string, Action[]> = {
  admin: [
    'dashboard.read',
    'users.read',
    'users.write',
    'wallets.read',
    'deposits.read',
    'deposits.write',
    'withdrawals.read',
    'withdrawals.write',
    'spot.orders.read',
    'spot.orders.write',
    'spot.trades.read',
    'futures.positions.read',
    'futures.orders.read',
    'binary.trades.read',
    'binary.trades.write'
  ],
  agent: [
    'dashboard.read',
    'users.read',
    'wallets.read',
    'deposits.read',
    'withdrawals.read',
    'spot.orders.read',
    'spot.trades.read',
    'futures.positions.read',
    'futures.orders.read',
    'binary.trades.read'
  ],
  support: ['dashboard.read', 'users.read']
};

