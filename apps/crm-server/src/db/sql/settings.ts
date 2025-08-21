export const listSettings = `SELECT id, \`key\`, \`value\`, status FROM settings ORDER BY id DESC`;

export const insertSetting = `INSERT INTO settings (\`key\`, \`value\`, status) VALUES (?, ?, ?)`;

export const updateSetting = `UPDATE settings SET \`key\` = ?, \`value\` = ?, status = ? WHERE id = ?`;

export const deleteSetting = `DELETE FROM settings WHERE id = ?`;
