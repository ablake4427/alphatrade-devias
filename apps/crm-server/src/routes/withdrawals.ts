import { Router, Request, Response, NextFunction } from 'express';
import { pool, assertTableExists } from '../db/db.js';
import { listWithdrawals, approveWithdrawal, rejectWithdrawal } from '../db/sql/withdrawals.js';
import { requirePerm } from '../middleware/auth.js';
import { rateLimit } from '../middleware/rateLimit.js';
import { HttpError } from '../middleware/error.js';

const router = Router();
const asyncHandler = (fn: any) => (req: Request, res: Response, next: NextFunction) =>
  Promise.resolve(fn(req, res, next)).catch(next);

router.get('/', requirePerm('withdrawals.read'), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('withdrawals'))) return res.json({ data: [] });
  const { status = null, user = null, date_from = '1970-01-01', date_to = '2100-01-01', limit = '20', offset = '0' } = req.query;
  const [rows] = await (pool.query as any)(listWithdrawals, [status, status, user, user, date_from, date_to, Number(limit), Number(offset)]);
  res.json({ data: rows });
}));

router.post('/approve/:id', requirePerm('withdrawals.write'), rateLimit(5, 60000), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('withdrawals'))) throw new HttpError(501, 'not_supported', 'not supported');
  const id = Number(req.params.id);
  await (pool.query as any)(approveWithdrawal, [id]);
  res.json({ ok: true });
}));

router.post('/reject/:id', requirePerm('withdrawals.write'), rateLimit(5, 60000), asyncHandler(async (req: Request, res: Response) => {
  if (!(await assertTableExists('withdrawals'))) throw new HttpError(501, 'not_supported', 'not supported');
  const id = Number(req.params.id);
  const { reason = '' } = req.body;
  await (pool.query as any)(rejectWithdrawal, [reason, id]);
  res.json({ ok: true });
}));

export default router;
