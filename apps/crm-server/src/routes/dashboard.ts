import { Router } from 'express';
import { query, assertTableExists } from '../db/db.js';

const router = Router();

router.get('/summary', async (_req, res) => {
  const summary: any = {};
  const orders = await query<any>('SELECT COUNT(*) as total, SUM(status="open") as open FROM orders');
  const deposits = await query<any>('SELECT COUNT(*) as total, SUM(status="pending") as pending, SUM(status="successful") as successful FROM deposits');
  const withdrawals = await query<any>('SELECT COUNT(*) as total, SUM(status="pending") as pending, SUM(status="approved") as approved, SUM(status="rejected") as rejected FROM withdrawals');
  summary.orders = orders[0];
  summary.deposits = deposits[0];
  summary.withdrawals = withdrawals[0];
  if (await assertTableExists('user_daily_metrics')) {
    const metrics = await query<any>(
      'SELECT SUM(yesterday_active) as yesterday, SUM(seven_day_active) as week FROM user_daily_metrics'
    );
    summary.retention = metrics[0];
  } else {
    summary.retention = { yesterday: 0, week: 0 };
  }
  res.json(summary);
});

export default router;
