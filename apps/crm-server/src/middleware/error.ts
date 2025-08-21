import { Request, Response, NextFunction } from 'express';
import pino from 'pino';

const logger = pino();

export class HttpError extends Error {
  status: number;
  code: string;
  constructor(status: number, code: string, message: string) {
    super(message);
    this.status = status;
    this.code = code;
  }
}

export function errorHandler(err: any, _req: Request, res: Response, _next: NextFunction) {
  const status = err.status || 500;
  const code = err.code || 'internal_error';
  logger.error({ err }, 'request error');
  res.status(status).json({ error: err.message || 'Internal error', code });
}
