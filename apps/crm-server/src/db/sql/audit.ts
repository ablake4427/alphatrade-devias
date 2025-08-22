export const listAuditLogs = `SELECT id, admin_id, action, meta, created_at FROM audit_logs ORDER BY id DESC LIMIT ? OFFSET ?`;
