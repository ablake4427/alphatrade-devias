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

const router = Router();

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
router.get('/p2p/ads', perm('p2p.read'), notImplemented);
router.post('/p2p/ads', perm('p2p.write'), notImplemented);
router.put('/p2p/ads/:id', perm('p2p.write'), notImplemented);
router.post('/p2p/ads/:id/toggle', perm('p2p.write'), notImplemented);
router.get('/p2p/trades', perm('p2p.read'), notImplemented);
router.get('/p2p/trades/:id', perm('p2p.read'), notImplemented);
router.post('/p2p/trades/:id/complete', perm('p2p.write'), notImplemented);
router.post('/p2p/trades/:id/message', perm('p2p.write'), notImplemented);
router.post('/p2p/disputes/:id/resolve', perm('p2p.write'), notImplemented);

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
