export type Action =
  | 'dashboard.read'
  | 'users.read'
  | 'users.write'
  | 'wallets.read'
  | 'deposits.read'
  | 'deposits.write'
  | 'withdrawals.read'
  | 'withdrawals.write'
  | 'reports.read';

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
    'reports.read'
  ],
  agent: ['dashboard.read', 'users.read', 'wallets.read', 'deposits.read', 'withdrawals.read', 'reports.read'],
  support: ['dashboard.read', 'users.read']
};

