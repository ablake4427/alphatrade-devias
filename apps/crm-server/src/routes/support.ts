import { Router, Request, Response, NextFunction } from 'express';
import { pool, assertTableExists } from '../db/db.js';
import * as sql from '../db/sql/support.js';
import { requirePerm } from '../middleware/auth.js';
import { HttpError } from '../middleware/error.js';

const router = Router();
const asyncHandler = (fn: any) => (req: Request, res: Response, next: NextFunction) =>
  Promise.resolve(fn(req, res, next)).catch(next);

router.get('/tickets', requirePerm('support.read'), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('support_tickets'))) return res.json({ data: [] });
  const { limit = '20', offset = '0' } = req.query;
  const [rows] = await (pool.query as any)(sql.listTickets, [Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

router.get('/tickets/:id', requirePerm('support.read'), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('support_tickets'))) throw new HttpError(404, 'not_found', 'ticket not found');
  const id = Number(req.params.id);
  const [tickets] = await (pool.query as any)(sql.getTicket, [id]);
  if ((tickets as any).length === 0) throw new HttpError(404, 'not_found', 'ticket not found');
  const [messages] = await (pool.query as any)(sql.getMessages, [id]);
  res.json({ ticket: (tickets as any)[0], messages });
}));

router.post('/tickets/:id/reply', requirePerm('support.write'), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('support_tickets'))) throw new HttpError(501, 'not_supported', 'not supported');
  const id = Number(req.params.id);
  const { message = '' } = req.body;
  if (!message) throw new HttpError(400, 'bad_request', 'message required');
  await (pool.query as any)(sql.insertMessage, [id, 'admin', message]);
  res.json({ ok: true });
}));

router.post('/tickets/:id/close', requirePerm('support.write'), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('support_tickets'))) throw new HttpError(501, 'not_supported', 'not supported');
  const id = Number(req.params.id);
  await (pool.query as any)(sql.closeTicket, [id]);
  res.json({ ok: true });
}));

export default router;
