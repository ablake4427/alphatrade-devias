import { Router, Request, Response, NextFunction } from 'express';
import { requirePerm } from '../middleware/auth.js';
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

// Support
router.get('/support/tickets', perm('support.read'), notImplemented);
router.get('/support/tickets/:id', perm('support.read'), notImplemented);
router.post('/support/tickets/:id/reply', perm('support.write'), notImplemented);
router.post('/support/tickets/:id/close', perm('support.write'), notImplemented);

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
router.get('/settings', perm('settings.read'), notImplemented);
router.put('/settings/:key', perm('settings.write'), notImplemented);

// System
router.get('/cron', perm('system.read'), notImplemented);
router.post('/cron/:id/toggle', perm('system.write'), notImplemented);
router.get('/audit', perm('system.read'), notImplemented);
router.get('/alerts', perm('system.read'), notImplemented);

export default router;
