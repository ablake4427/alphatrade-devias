import { Request, Response, NextFunction } from 'express';
import jwt from 'jsonwebtoken';
import type { JwtPayload } from '@alphatrade/shared';
import { rolePermissions, Action } from '../auth/permissions.js';

export function requireAuth(req: Request, res: Response, next: NextFunction) {
  const auth = req.headers.authorization;
  if (!auth) return res.status(401).end();
  try {
    const token = auth.split(' ')[1];
    const payload = jwt.verify(token, process.env.JWT_SECRET!) as JwtPayload;
    payload.perms = payload.perms && payload.perms.length ? payload.perms : rolePermissions[payload.role] || [];
    (req as any).user = payload;
    next();
  } catch {
    res.status(401).end();
  }
}

export function requirePerm(action: Action) {
  return (req: Request, res: Response, next: NextFunction) => {
    const user = (req as any).user as JwtPayload | undefined;
    if (!user) return res.status(401).end();
    const allowed = user.perms?.includes(action) || user.perms?.includes('*');
    if (!allowed) return res.status(403).end();
    next();
  };
}
