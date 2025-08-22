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
  | 'reports.read';
  | 'support.read'
  | 'support.write';

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
    'reports.read'
  ],
  agent: ['dashboard.read', 'users.read', 'wallets.read', 'deposits.read', 'withdrawals.read', 'reports.read'],
  support: ['dashboard.read', 'users.read']
    'support.read',
    'support.write'
  ],
  agent: ['dashboard.read', 'users.read', 'wallets.read', 'deposits.read', 'withdrawals.read'],
  support: ['dashboard.read', 'users.read', 'support.read', 'support.write']
};

