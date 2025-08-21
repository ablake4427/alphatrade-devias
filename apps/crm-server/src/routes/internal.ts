import { Router, Request, Response, NextFunction } from 'express';
import { requirePerm } from '../middleware/auth.js';
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

// Support
router.get('/support/tickets', perm('support.read'), notImplemented);
router.get('/support/tickets/:id', perm('support.read'), notImplemented);
router.post('/support/tickets/:id/reply', perm('support.write'), notImplemented);
router.post('/support/tickets/:id/close', perm('support.write'), notImplemented);

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
