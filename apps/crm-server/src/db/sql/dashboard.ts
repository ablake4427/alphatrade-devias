export const ordersSummary = `SELECT COUNT(*) as total, SUM(status="open") as open FROM orders`;

export const depositsSummary = `SELECT COUNT(*) as total, SUM(status="pending") as pending, SUM(status="successful") as successful FROM deposits`;

export const withdrawalsSummary = `SELECT COUNT(*) as total, SUM(status="pending") as pending, SUM(status="approved") as approved, SUM(status="rejected") as rejected FROM withdrawals`;

export const orders24h = `SELECT COUNT(*) as orders_24h FROM orders WHERE created_at >= NOW() - INTERVAL 1 DAY`;

export const deposits24h = `SELECT COUNT(*) as deposits_24h FROM deposits WHERE created_at >= NOW() - INTERVAL 1 DAY`;

export const withdrawals24h = `SELECT COUNT(*) as withdrawals_24h FROM withdrawals WHERE created_at >= NOW() - INTERVAL 1 DAY`;

export const topMarkets = `SELECT market, SUM(volume) as vol FROM trades GROUP BY market ORDER BY vol DESC LIMIT 5`;

export const tasksDueToday = `SELECT COUNT(*) as tasks_due_today FROM tasks WHERE DATE(due_at) = CURRENT_DATE`;

export const leadsNew7d = `SELECT COUNT(*) as leads_new_7d FROM leads WHERE created_at >= NOW() - INTERVAL 7 DAY`;
