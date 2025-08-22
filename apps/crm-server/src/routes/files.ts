import { Router, Request, Response, NextFunction } from 'express';
import multer from 'multer';
import path from 'path';
import fs from 'fs';
import { query } from '../db/db.js';

const asyncHandler = (fn: any) => (req: Request, res: Response, next: NextFunction) =>
  Promise.resolve(fn(req, res, next)).catch(next);

const router = Router();

const storageRoot = process.env.FILE_STORAGE_ROOT || path.join(process.cwd(), 'storage');
const upload = multer({ dest: path.join(storageRoot, 'tmp') });

router.post('/upload', upload.single('file'), asyncHandler(async (req: Request, res: Response) => {
  if (!req.file) return res.status(400).json({ error: 'No file' });
  const timestamp = Date.now();
  const random = Math.random().toString(36).slice(2, 8);
  const ext = path.extname(req.file.originalname);
  const filename = `${timestamp}_${random}${ext}`;
  const destDir = path.join(storageRoot, 'attachments');
  await fs.promises.mkdir(destDir, { recursive: true });
  const dest = path.join(destDir, filename);
  await fs.promises.rename(req.file.path, dest);
  await query('INSERT INTO attachments (file, original_name) VALUES (?, ?)', [filename, req.file.originalname]);
  res.json({ file: filename, original: req.file.originalname });
}));

export default router;
