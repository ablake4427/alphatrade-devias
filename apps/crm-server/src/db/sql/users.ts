export const listUsers = (search: string) => `SELECT id, email, username, status, kyc FROM users WHERE email LIKE ? OR username LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?`;

export const getUser = `SELECT id, email, username, status, kyc, crm_status FROM users WHERE id = ?`;

export const updateUser = `UPDATE users SET email = ?, status = ?, crm_status = ? WHERE id = ?`;

export const userLabels = `SELECT l.id, l.name FROM labels l JOIN label_user lu ON lu.label_id = l.id WHERE lu.user_id = ?`;

export const ensureLabel = `INSERT INTO labels (name) VALUES (?) ON DUPLICATE KEY UPDATE name = VALUES(name)`;

export const attachLabel = `INSERT IGNORE INTO label_user (user_id, label_id) VALUES (?, ?)`;

export const detachLabel = `DELETE FROM label_user WHERE user_id = ? AND label_id = ?`;

export const insertReminder = `INSERT INTO reminders (user_id, message, remind_at) VALUES (?, ?, ?)`;

export const userLogins = `SELECT * FROM user_logins WHERE user_id = ? ORDER BY created_at DESC LIMIT ?`;

export const insertAuditLog = `INSERT INTO audit_logs (admin_id, action, meta) VALUES (?, ?, ?)`;
