import { Router, Request, Response, NextFunction } from 'express';
import { query, assertTableExists } from '../db/db.js';
import { requirePerm } from '../middleware/auth.js';
import {
  listUsers,
  getUser,
  updateUser,
  userLabels,
  ensureLabel,
  attachLabel,
  detachLabel,
  insertReminder,
  userLogins,
  insertAuditLog
} from '../db/sql/users.js';
import { rateLimit } from '../middleware/rateLimit.js';
import { HttpError } from '../middleware/error.js';
import jwt from 'jsonwebtoken';

const router = Router();

const asyncHandler = (fn: any) => (req: Request, res: Response, next: NextFunction) =>
  Promise.resolve(fn(req, res, next)).catch(next);

router.get('/', requirePerm('users.read'), asyncHandler(async (req: Request, res: Response) => {
  const { search = '', limit = '20', offset = '0' } = req.query;
  const rows = await query<any>(listUsers(search as string), [
    `%${search}%`,
    `%${search}%`,
    Number(limit),
    Number(offset)
  ]);
  res.json({ data: rows });
}));

router.get('/:id', requirePerm('users.read'), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  const userRows = await query<any>(getUser, [id]);
  if (!userRows[0]) throw new HttpError(404, 'not_found', 'User not found');
  const labels = await query<any>(userLabels, [id]);
  res.json({ user: userRows[0], labels });
}));

router.put('/:id', requirePerm('users.write'), rateLimit(10, 60000), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  const { email, status, crm_status } = req.body;
  await query(updateUser, [email, status, crm_status, id]);
  res.json({ ok: true });
}));

router.post('/:id/labels', requirePerm('users.write'), rateLimit(10, 60000), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  const { name } = req.body;
  await query(ensureLabel, [name]);
  const label = await query<any>('SELECT id FROM labels WHERE name = ? LIMIT 1', [name]);
  await query(attachLabel, [id, label[0].id]);
  res.json({ ok: true });
}));

router.delete('/:id/labels/:labelId', requirePerm('users.write'), rateLimit(10, 60000), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  const labelId = Number(req.params.labelId);
  await query(detachLabel, [id, labelId]);
  res.json({ ok: true });
}));

router.post('/:id/reminders', requirePerm('users.write'), rateLimit(10, 60000), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  const { message, remind_at } = req.body;
  await query(insertReminder, [id, message, remind_at]);
  res.json({ ok: true });
}));

router.get('/:id/logins', requirePerm('users.read'), asyncHandler(async (req: Request, res: Response) => {
  const id = Number(req.params.id);
  if (!(await assertTableExists('user_logins'))) return res.json([]);
  const rows = await query<any>(userLogins, [id, 20]);
  res.json(rows);
}));

router.post('/:id/impersonate', requirePerm('users.read'), rateLimit(5, 60000), asyncHandler(async (req: Request, res: Response) => {
  const userId = Number(req.params.id);
  const admin = (req as any).user;
  await query(insertAuditLog, [admin.admin_id, 'impersonate', JSON.stringify({ userId })]);
  const blob = jwt.sign({ user_id: userId }, process.env.JWT_SECRET!, { expiresIn: '5m' });
  res.json({ token: blob });
}));

export default router;
