import { Router, Request, Response, NextFunction } from 'express';
import { query, assertTableExists } from '../db/db.js';
import { userWallets } from '../db/sql/wallets.js';
import { requirePerm } from '../middleware/auth.js';

const router = Router();
const asyncHandler = (fn: any) => (req: Request, res: Response, next: NextFunction) =>
  Promise.resolve(fn(req, res, next)).catch(next);

router.get('/:userId', requirePerm('wallets.read'), asyncHandler(async (req: Request, res: Response) => {
  const userId = Number(req.params.userId);
  if (!(await assertTableExists('wallets'))) return res.json([]);
  const rows = await query<any>(userWallets, [userId]);
  res.json(rows);
}));

export default router;
