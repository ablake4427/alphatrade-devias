import { Router, Request, Response, NextFunction } from 'express';
import { requirePerm } from '../middleware/auth.js';
import { query } from '../db/db.js';
import * as settingsSql from '../db/sql/settings.js';
import * as cronSql from '../db/sql/cron.js';
import * as auditSql from '../db/sql/audit.js';
import * as alertsSql from '../db/sql/alerts.js';

const router = Router();

const perm = (action: string) => requirePerm(action as any);

const asyncHandler = (fn: any) => (req: Request, res: Response, next: NextFunction) =>
  Promise.resolve(fn(req, res, next)).catch(next);

function notImplemented(_req: any, res: any) {
  res.status(501).json({ error: 'not implemented' });
}

// Trading - Spot
router.get('/spot/orders', perm('spot.orders.read'), notImplemented);
router.delete('/spot/orders/:id', perm('spot.orders.write'), notImplemented);
router.get('/spot/trades', perm('spot.trades.read'), notImplemented);

// Trading - Futures
router.get('/futures/positions', perm('futures.positions.read'), notImplemented);
router.get('/futures/orders', perm('futures.orders.read'), notImplemented);

// Trading - Binary
router.get('/binary/trades', perm('binary.trades.read'), notImplemented);
router.post('/binary/trades/:id/refund', perm('binary.trades.write'), notImplemented);

// P2P
router.get('/p2p/ads', perm('p2p.read'), notImplemented);
router.post('/p2p/ads', perm('p2p.write'), notImplemented);
router.put('/p2p/ads/:id', perm('p2p.write'), notImplemented);
router.post('/p2p/ads/:id/toggle', perm('p2p.write'), notImplemented);
router.get('/p2p/trades', perm('p2p.read'), notImplemented);
router.get('/p2p/trades/:id', perm('p2p.read'), notImplemented);
router.post('/p2p/trades/:id/complete', perm('p2p.write'), notImplemented);
router.post('/p2p/trades/:id/message', perm('p2p.write'), notImplemented);
router.post('/p2p/disputes/:id/resolve', perm('p2p.write'), notImplemented);

// CRM
router.get('/crm/leads', perm('crm.read'), notImplemented);
router.post('/crm/leads', perm('crm.write'), notImplemented);
router.put('/crm/leads/:id', perm('crm.write'), notImplemented);
router.get('/crm/contacts', perm('crm.read'), notImplemented);
router.post('/crm/contacts', perm('crm.write'), notImplemented);
router.get('/crm/opportunities', perm('crm.read'), notImplemented);
router.post('/crm/opportunities', perm('crm.write'), notImplemented);
router.put('/crm/opportunities/:id', perm('crm.write'), notImplemented);
router.post('/crm/opportunities/:id/stage', perm('crm.write'), notImplemented);
router.get('/crm/tasks', perm('crm.read'), notImplemented);
router.post('/crm/tasks', perm('crm.write'), notImplemented);
router.get('/crm/notes', perm('crm.read'), notImplemented);
router.post('/crm/notes', perm('crm.write'), notImplemented);
router.get('/crm/chat', perm('crm.read'), notImplemented);
router.post('/crm/chat', perm('crm.write'), notImplemented);

// Reports
router.get('/reports/transactions', perm('reports.read'), notImplemented);
router.get('/reports/logins', perm('reports.read'), notImplemented);
router.get('/reports/notifications', perm('reports.read'), notImplemented);
router.get('/reports/agent-performance', perm('reports.read'), notImplemented);

// Settings
router.get('/settings', perm('settings.read'), asyncHandler(async (_req: Request, res: Response) => {
  const rows = await query<any>(settingsSql.listSettings);
  res.json({ data: rows });
}));

router.post('/settings', perm('settings.write'), asyncHandler(async (req: Request, res: Response) => {
  const { key, value, status } = req.body;
  await query(settingsSql.insertSetting, [key, value, status]);
  res.json({ ok: true });
}));

router.put('/settings/:id', perm('settings.write'), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  const { key, value, status } = req.body;
  await query(settingsSql.updateSetting, [key, value, status, id]);
  res.json({ ok: true });
}));

router.delete('/settings/:id', perm('settings.write'), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  await query(settingsSql.deleteSetting, [id]);
  res.json({ ok: true });
}));

// System
router.get('/cron', perm('system.read'), asyncHandler(async (_req: Request, res: Response) => {
  const rows = await query<any>(cronSql.listCronJobs);
  res.json({ data: rows });
}));

router.post('/cron', perm('system.write'), asyncHandler(async (req: Request, res: Response) => {
  const { name, schedule, status } = req.body;
  await query(cronSql.insertCronJob, [name, schedule, status]);
  res.json({ ok: true });
}));

router.put('/cron/:id', perm('system.write'), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  const { name, schedule, status } = req.body;
  await query(cronSql.updateCronJob, [name, schedule, status, id]);
  res.json({ ok: true });
}));

router.delete('/cron/:id', perm('system.write'), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  await query(cronSql.deleteCronJob, [id]);
  res.json({ ok: true });
}));

router.post('/cron/:id/toggle', perm('system.write'), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  await query(cronSql.toggleCronJob, [id]);
  res.json({ ok: true });
}));

router.get('/audit', perm('system.read'), asyncHandler(async (req: Request, res: Response) => {
  const { limit = '20', offset = '0' } = req.query;
  const rows = await query<any>(auditSql.listAuditLogs, [Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

router.get('/alerts', perm('system.read'), asyncHandler(async (req: Request, res: Response) => {
  const { limit = '20', offset = '0' } = req.query;
  const rows = await query<any>(alertsSql.listAlerts, [Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

export default router;
