export const transactions = `
  SELECT DATE(created_at) AS date,
         COUNT(*) AS total,
         SUM(amount) AS volume,
         SUM(CASE WHEN trx_type = '+' THEN amount ELSE 0 END) AS credit,
         SUM(CASE WHEN trx_type = '-' THEN amount ELSE 0 END) AS debit
  FROM transactions
  GROUP BY DATE(created_at)
  ORDER BY date DESC
  LIMIT 30
`;

export const logins = `
  SELECT DATE(created_at) AS date,
         COUNT(*) AS logins
  FROM user_logins
  GROUP BY DATE(created_at)
  ORDER BY date DESC
  LIMIT 30
`;

export const notifications = `
  SELECT DATE(created_at) AS date,
         COUNT(*) AS notifications,
         SUM(is_read = 0) AS unread
  FROM admin_notifications
  GROUP BY DATE(created_at)
  ORDER BY date DESC
  LIMIT 30
`;

export const agentPerformance = `
  SELECT a.id,
         a.username,
         COUNT(u.id) AS users
  FROM admins a
  LEFT JOIN users u ON u.ref_by = a.id
  GROUP BY a.id, a.username
  ORDER BY users DESC
`;
