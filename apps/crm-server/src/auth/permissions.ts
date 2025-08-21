export type Action =
  | 'dashboard.read'
  | 'users.read'
  | 'users.write'
  | 'wallets.read'
  | 'deposits.read'
  | 'deposits.write'
  | 'withdrawals.read'
  | 'withdrawals.write'
  | 'crm.read'
  | 'crm.write';

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
    'crm.read',
    'crm.write'
  ],
  agent: [
    'dashboard.read',
    'users.read',
    'wallets.read',
    'deposits.read',
    'withdrawals.read',
    'crm.read',
    'crm.write'
  ],
  support: ['dashboard.read', 'users.read']
};

