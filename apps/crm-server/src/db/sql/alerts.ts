export const listAlerts = `SELECT id, message, level, created_at FROM alerts ORDER BY id DESC LIMIT ? OFFSET ?`;
