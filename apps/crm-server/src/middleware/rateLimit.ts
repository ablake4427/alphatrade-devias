import { Request, Response, NextFunction } from 'express';

export function rateLimit(tokens: number, intervalMs: number) {
  const buckets = new Map<string, { tokens: number; last: number }>();
  return (req: Request, res: Response, next: NextFunction) => {
    const key = req.ip || 'global';
    const now = Date.now();
    const bucket = buckets.get(key) || { tokens, last: now };
    const delta = now - bucket.last;
    bucket.tokens = Math.min(tokens, bucket.tokens + (delta / intervalMs) * tokens);
    bucket.last = now;
    if (bucket.tokens < 1) {
      res.status(429).json({ error: 'rate_limited', code: 'rate_limited' });
      return;
    }
    bucket.tokens -= 1;
    buckets.set(key, bucket);
    next();
  };
}
