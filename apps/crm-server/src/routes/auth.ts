
import { Router, Request, Response, NextFunction } from 'express';


import { Router, Request, Response, NextFunction } from 'express';

import { Router } from 'express';


import { query } from '../db/db.js';
import bcrypt from 'bcrypt';
import jwt from 'jsonwebtoken';
import { z } from 'zod';
import type { JwtPayload } from '@alphatrade/shared';


import { rateLimit } from '../middleware/rateLimit.js';
import { HttpError } from '../middleware/error.js';

const asyncHandler = (fn: any) => (req: Request, res: Response, next: NextFunction) =>
  Promise.resolve(fn(req, res, next)).catch(next);


const router = Router();
const blacklist = new Set<string>();

const loginSchema = z.object({
  username: z.string(),
  password: z.string()
});


router.post('/login', rateLimit(5, 60000), asyncHandler(async (req: Request, res: Response) => {


router.post('/login', rateLimit(5, 60000), asyncHandler(async (req: Request, res: Response) => {

router.post('/login', async (req, res) => {


  const parsed = loginSchema.safeParse(req.body);
  if (!parsed.success) return res.status(400).json({ error: 'Invalid payload' });
  const { username, password } = parsed.data;
  const rows = await query<any>(
    'SELECT id, email, password, role FROM admins WHERE email = ? OR username = ? LIMIT 1',
    [username, username]
  );
  const admin = rows[0];

  if (!admin) throw new HttpError(401, 'invalid', 'Invalid credentials');
  const match = await bcrypt.compare(password, admin.password);
  if (!match) throw new HttpError(401, 'invalid', 'Invalid credentials');
  const payload: JwtPayload = { admin_id: admin.id, role: admin.role, perms: [] };
  const token = jwt.sign(payload, process.env.JWT_SECRET!, { expiresIn: '1h' });
  res.json({ token });
}));

router.get('/me', asyncHandler(async (req: Request, res: Response) => {


  if (!admin) return res.status(401).json({ error: 'Invalid credentials' });
  const match = await bcrypt.compare(password, admin.password);
  if (!match) return res.status(401).json({ error: 'Invalid credentials' });
  const payload: JwtPayload = { admin_id: admin.id, role: admin.role, perms: [] };
  const token = jwt.sign(payload, process.env.JWT_SECRET!, { expiresIn: '1h' });
  res.json({ token });
});

router.get('/me', async (req, res) => {


  const auth = req.headers.authorization;
  if (!auth) return res.status(401).end();
  const token = auth.split(' ')[1];
  if (blacklist.has(token)) return res.status(401).end();
  try {
    const payload = jwt.verify(token, process.env.JWT_SECRET!) as JwtPayload;
    const rows = await query<any>('SELECT id, email, role FROM admins WHERE id = ? LIMIT 1', [payload.admin_id]);
    if (!rows[0]) return res.status(404).end();
    res.json(rows[0]);
  } catch {
    res.status(401).end();
  }

}));

router.post('/logout', (req: Request, res: Response) => {


}));

router.post('/logout', (req: Request, res: Response) => {

});

router.post('/logout', (req, res) => {


  const auth = req.headers.authorization;
  if (auth) {
    const token = auth.split(' ')[1];
    blacklist.add(token);
  }
  res.json({ ok: true });
});

export default router;
