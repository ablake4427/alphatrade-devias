import { Router, Request, Response, NextFunction } from 'express';
import { requirePerm } from '../middleware/auth.js';
import { query } from '../db/db.js';
import {
  listSpotOrders,
  cancelSpotOrder,
  listSpotTrades,
  listFuturesPositions,
  listFuturesOrders,
  listBinaryTrades,
  refundBinaryTrade
} from '../db/sql/trading.js';
import { pool, assertTableExists } from '../db/db.js';
import { HttpError } from '../middleware/error.js';
import {
  listAds,
  insertAd,
  updateAd as sqlUpdateAd,
  toggleAd,
  listTrades,
  getTrade,
  completeTrade,
  insertTradeMessage,
  resolveDispute
} from '../db/sql/p2p.js';
import { query } from '../db/db.js';
import {
  listLeads,
  insertLead,
  updateLead,
  listContacts,
  insertContact,
  listOpportunities,
  insertOpportunity,
  updateOpportunity,
  updateOpportunityStage,
  listTasks,
  insertTask,
  listNotes,
  insertNote,
  listChat,
  insertChat
} from '../db/sql/crm.js';
import { query, assertTableExists } from '../db/db.js';
import * as reports from '../db/sql/reports.js';
import { requirePerm } from '../middleware/auth.js';
import { query } from '../db/db.js';
import * as settingsSql from '../db/sql/settings.js';
import * as cronSql from '../db/sql/cron.js';
import * as auditSql from '../db/sql/audit.js';
import * as alertsSql from '../db/sql/alerts.js';

const router = Router();

const asyncHandler = (fn: any) => (req: Request, res: Response, next: NextFunction) =>
  Promise.resolve(fn(req, res, next)).catch(next);

const perm = (action: string) => requirePerm(action as any);

const asyncHandler = (fn: any) => (req: Request, res: Response, next: NextFunction) =>
  Promise.resolve(fn(req, res, next)).catch(next);

function notImplemented(_req: any, res: any) {
  res.status(501).json({ error: 'not implemented' });
}

// Trading - Spot
router.get('/spot/orders', perm('spot.orders.read'), asyncHandler(async (req: Request, res: Response) => {
  const { limit = '20', offset = '0' } = req.query;
  const rows = await query<any>(listSpotOrders, [Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

router.delete('/spot/orders/:id', perm('spot.orders.write'), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  await query(cancelSpotOrder, [id]);
  res.json({ ok: true });
}));

router.get('/spot/trades', perm('spot.trades.read'), asyncHandler(async (req: Request, res: Response) => {
  const { limit = '20', offset = '0' } = req.query;
  const rows = await query<any>(listSpotTrades, [Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

// Trading - Futures
router.get('/futures/positions', perm('futures.positions.read'), asyncHandler(async (req: Request, res: Response) => {
  const { limit = '20', offset = '0' } = req.query;
  const rows = await query<any>(listFuturesPositions, [Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

router.get('/futures/orders', perm('futures.orders.read'), asyncHandler(async (req: Request, res: Response) => {
  const { limit = '20', offset = '0' } = req.query;
  const rows = await query<any>(listFuturesOrders, [Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

// Trading - Binary
router.get('/binary/trades', perm('binary.trades.read'), asyncHandler(async (req: Request, res: Response) => {
  const { status = null, limit = '20', offset = '0' } = req.query;
  const rows = await query<any>(listBinaryTrades, [status, status, Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

router.post('/binary/trades/:id/refund', perm('binary.trades.write'), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  await query(refundBinaryTrade, [id]);
  res.json({ ok: true });
}));

// P2P
router.get('/p2p/ads', perm('p2p.read'), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('p2p_ads'))) return res.json({ data: [] });
  const { limit = '20', offset = '0' } = req.query;
  const [rows] = await (pool.query as any)(listAds, [Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

router.post('/p2p/ads', perm('p2p.write'), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('p2p_ads'))) throw new HttpError(501, 'not_supported', 'not supported');
  const {
    type,
    user_id,
    asset_id,
    fiat_id,
    payment_window_id,
    price_type,
    price,
    price_margin,
    minimum_amount,
    maximum_amount,
    payment_details = null,
    terms_of_trade = null,
    auto_replay_text = null,
    status = 1
  } = req.body;
  const [result] = await (pool.query as any)(insertAd, [
    type,
    user_id,
    asset_id,
    fiat_id,
    payment_window_id,
    price_type,
    price,
    price_margin,
    minimum_amount,
    maximum_amount,
    payment_details,
    terms_of_trade,
    auto_replay_text,
    status
  ]);
  res.json({ id: (result as any).insertId });
}));

router.put('/p2p/ads/:id', perm('p2p.write'), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('p2p_ads'))) throw new HttpError(501, 'not_supported', 'not supported');
  const id = Number(req.params.id);
  const {
    type,
    user_id,
    asset_id,
    fiat_id,
    payment_window_id,
    price_type,
    price,
    price_margin,
    minimum_amount,
    maximum_amount,
    payment_details = null,
    terms_of_trade = null,
    auto_replay_text = null,
    status = 1
  } = req.body;
  await (pool.query as any)(sqlUpdateAd, [
    type,
    user_id,
    asset_id,
    fiat_id,
    payment_window_id,
    price_type,
    price,
    price_margin,
    minimum_amount,
    maximum_amount,
    payment_details,
    terms_of_trade,
    auto_replay_text,
    status,
    id
  ]);
  res.json({ ok: true });
}));

router.post('/p2p/ads/:id/toggle', perm('p2p.write'), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('p2p_ads'))) throw new HttpError(501, 'not_supported', 'not supported');
  const id = Number(req.params.id);
  await (pool.query as any)(toggleAd, [id]);
  res.json({ ok: true });
}));

router.get('/p2p/trades', perm('p2p.read'), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('p2p_trades'))) return res.json({ data: [] });
  const { limit = '20', offset = '0' } = req.query;
  const [rows] = await (pool.query as any)(listTrades, [Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

router.get('/p2p/trades/:id', perm('p2p.read'), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('p2p_trades'))) throw new HttpError(404, 'not_found', 'not found');
  const id = Number(req.params.id);
  const [rows] = await (pool.query as any)(getTrade, [id]);
  res.json({ data: rows[0] || null });
}));

router.post('/p2p/trades/:id/complete', perm('p2p.write'), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('p2p_trades'))) throw new HttpError(501, 'not_supported', 'not supported');
  const id = Number(req.params.id);
  await (pool.query as any)(completeTrade, [id]);
  res.json({ ok: true });
}));

router.post('/p2p/trades/:id/message', perm('p2p.write'), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('p2p_trade_messages'))) throw new HttpError(501, 'not_supported', 'not supported');
  const tradeId = Number(req.params.id);
  const { message = '' } = req.body;
  const adminId = (req as any).user?.id || 0;
  await (pool.query as any)(insertTradeMessage, [tradeId, adminId, message]);
  res.json({ ok: true });
}));

router.post('/p2p/disputes/:id/resolve', perm('p2p.write'), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('p2p_trades'))) throw new HttpError(501, 'not_supported', 'not supported');
  const id = Number(req.params.id);
  await (pool.query as any)(resolveDispute, [id]);
  res.json({ ok: true });
}));

// CRM
router.get('/crm/leads', perm('crm.read'), asyncHandler(async (req: Request, res: Response) => {
  const { limit = '20', offset = '0' } = req.query;
  const rows = await query(listLeads, [Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

router.post('/crm/leads', perm('crm.write'), asyncHandler(async (req: Request, res: Response) => {
  const { name, email, status } = req.body;
  await query(insertLead, [name, email, status]);
  res.json({ ok: true });
}));

router.put('/crm/leads/:id', perm('crm.write'), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  const { name, email, status } = req.body;
  await query(updateLead, [name, email, status, id]);
  res.json({ ok: true });
}));

router.get('/crm/contacts', perm('crm.read'), asyncHandler(async (req: Request, res: Response) => {
  const { limit = '20', offset = '0' } = req.query;
  const rows = await query(listContacts, [Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

router.post('/crm/contacts', perm('crm.write'), asyncHandler(async (req: Request, res: Response) => {
  const { name, email, phone } = req.body;
  await query(insertContact, [name, email, phone]);
  res.json({ ok: true });
}));

router.get('/crm/opportunities', perm('crm.read'), asyncHandler(async (req: Request, res: Response) => {
  const { limit = '20', offset = '0' } = req.query;
  const rows = await query(listOpportunities, [Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

router.post('/crm/opportunities', perm('crm.write'), asyncHandler(async (req: Request, res: Response) => {
  const { name, value, stage } = req.body;
  await query(insertOpportunity, [name, value, stage]);
  res.json({ ok: true });
}));

router.put('/crm/opportunities/:id', perm('crm.write'), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  const { name, value, stage } = req.body;
  await query(updateOpportunity, [name, value, stage, id]);
  res.json({ ok: true });
}));

router.post('/crm/opportunities/:id/stage', perm('crm.write'), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  const { stage } = req.body;
  await query(updateOpportunityStage, [stage, id]);
  res.json({ ok: true });
}));

router.get('/crm/tasks', perm('crm.read'), asyncHandler(async (req: Request, res: Response) => {
  const { limit = '20', offset = '0' } = req.query;
  const rows = await query(listTasks, [Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

router.post('/crm/tasks', perm('crm.write'), asyncHandler(async (req: Request, res: Response) => {
  const { title, due_date, status } = req.body;
  await query(insertTask, [title, due_date, status]);
  res.json({ ok: true });
}));

router.get('/crm/notes', perm('crm.read'), asyncHandler(async (req: Request, res: Response) => {
  const { limit = '20', offset = '0' } = req.query;
  const rows = await query(listNotes, [Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

router.post('/crm/notes', perm('crm.write'), asyncHandler(async (req: Request, res: Response) => {
  const { entity, entity_id, note } = req.body;
  await query(insertNote, [entity, entity_id, note]);
  res.json({ ok: true });
}));

router.get('/crm/chat', perm('crm.read'), asyncHandler(async (req: Request, res: Response) => {
  const { limit = '20', offset = '0' } = req.query;
  const rows = await query(listChat, [Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

router.post('/crm/chat', perm('crm.write'), asyncHandler(async (req: Request, res: Response) => {
  const { sender_id, message } = req.body;
  await query(insertChat, [sender_id, message]);
  res.json({ ok: true });
}));

// Reports
router.get('/reports/transactions', perm('reports.read'), asyncHandler(async (_req: Request, res: Response) => {
  if (!(await assertTableExists('transactions'))) return res.json([]);
  const rows = await query<any>(reports.transactions);
  res.json(rows);
}));
router.get('/reports/logins', perm('reports.read'), asyncHandler(async (_req: Request, res: Response) => {
  if (!(await assertTableExists('user_logins'))) return res.json([]);
  const rows = await query<any>(reports.logins);
  res.json(rows);
}));
router.get('/reports/notifications', perm('reports.read'), asyncHandler(async (_req: Request, res: Response) => {
  if (!(await assertTableExists('admin_notifications'))) return res.json([]);
  const rows = await query<any>(reports.notifications);
  res.json(rows);
}));
router.get('/reports/agent-performance', perm('reports.read'), asyncHandler(async (_req: Request, res: Response) => {
  if (!(await assertTableExists('admins')) || !(await assertTableExists('users'))) return res.json([]);
  const rows = await query<any>(reports.agentPerformance);
  res.json(rows);
}));

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
