import { Router, Request, Response, NextFunction } from 'express';
import { pool, query } from '../db/db.js';
import {
  createCommentsTable,
  listComments,
  getComment,
  insertComment,
  updateComment as updateCommentSql,
  deleteComment as deleteCommentSql,
} from '../db/sql/comments.js';

const router = Router();

const asyncHandler = (fn: any) => (req: Request, res: Response, next: NextFunction) =>
  Promise.resolve(fn(req, res, next)).catch(next);

router.use(asyncHandler(async (_req: Request, _res: Response, next: NextFunction) => {
  await query(createCommentsTable);
  next();
}));

router.get('/', asyncHandler(async (req: Request, res: Response) => {
  const { entityId } = req.query as { entityId: string };
  const rows = await query<any>(listComments, [entityId]);
  res.json(rows);
}));

router.post('/', asyncHandler(async (req: Request, res: Response) => {
  const { entityId } = req.query as { entityId: string };
  const { content } = req.body;
  const [result]: any = await pool.query(insertComment, [entityId, content]);
  const id = result.insertId;
  const [rows] = await pool.query<any[]>(getComment, [id]);
  res.json(rows[0]);
}));

router.put('/:id', asyncHandler(async (req: Request, res: Response) => {
  const { entityId } = req.query as { entityId: string };
  const { content } = req.body;
  const id = Number(req.params.id);
  await pool.query(updateCommentSql, [content, id, entityId]);
  const [rows] = await pool.query<any[]>(getComment, [id]);
  res.json(rows[0]);
}));

router.delete('/:id', asyncHandler(async (req: Request, res: Response) => {
  const { entityId } = req.query as { entityId: string };
  const id = Number(req.params.id);
  await pool.query(deleteCommentSql, [id, entityId]);
  res.json({ ok: true });
}));

export default router;
