export type Action =
  | 'dashboard.read'
  | 'users.read'
  | 'users.write';

export const rolePermissions: Record<string, Action[]> = {
  admin: ['dashboard.read', 'users.read', 'users.write'],
  agent: ['dashboard.read', 'users.read'],
  support: ['dashboard.read']
};
