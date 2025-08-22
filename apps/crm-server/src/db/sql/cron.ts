export const listCronJobs = `SELECT id, name, schedule, status FROM cron_jobs ORDER BY id DESC`;

export const insertCronJob = `INSERT INTO cron_jobs (name, schedule, status) VALUES (?, ?, ?)`;

export const updateCronJob = `UPDATE cron_jobs SET name = ?, schedule = ?, status = ? WHERE id = ?`;

export const deleteCronJob = `DELETE FROM cron_jobs WHERE id = ?`;

export const toggleCronJob = `UPDATE cron_jobs SET status = IF(status='active','inactive','active') WHERE id = ?`;
