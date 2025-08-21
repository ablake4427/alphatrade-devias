
import { Router, Request, Response, NextFunction } from 'express';
import { query, assertTableExists } from '../db/db.js';
import * as sql from '../db/sql/dashboard.js';

const asyncHandler = (fn: any) => (req: Request, res: Response, next: NextFunction) =>
  Promise.resolve(fn(req, res, next)).catch(next);

const router = Router();

router.get('/summary', asyncHandler(async (_req: Request, res: Response) => {
  const summary: any = {};
  const orders = await query<any>(sql.ordersSummary);
  const deposits = await query<any>(sql.depositsSummary);
  const withdrawals = await query<any>(sql.withdrawalsSummary);
  const o24 = await query<any>(sql.orders24h);
  const d24 = await query<any>(sql.deposits24h);
  const w24 = await query<any>(sql.withdrawals24h);
  summary.orders = { ...orders[0], ...o24[0] };
  summary.deposits = { ...deposits[0], ...d24[0] };
  summary.withdrawals = { ...withdrawals[0], ...w24[0] };
  if (await assertTableExists('user_daily_metrics')) {
    const metrics = await query<any>('SELECT SUM(yesterday_active) as yesterday, SUM(seven_day_active) as week FROM user_daily_metrics');

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

  summary.top_markets = (await assertTableExists('trades')) ? await query<any>(sql.topMarkets) : [];
  summary.tasks_due_today = (await assertTableExists('tasks')) ? (await query<any>(sql.tasksDueToday))[0].tasks_due_today : 0;
  summary.leads_new_7d = (await assertTableExists('leads')) ? (await query<any>(sql.leadsNew7d))[0].leads_new_7d : 0;
  res.json(summary);
}));
    
  res.json(summary);
});


export default router;
