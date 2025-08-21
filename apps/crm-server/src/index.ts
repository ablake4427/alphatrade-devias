import express from 'express';
import authRoutes from './routes/auth.js';
import fileRoutes from './routes/files.js';
import dashboardRoutes from './routes/dashboard.js';
import { requireAuth } from './middleware/auth.js';
import pino from 'pino';

const app = express();
const logger = pino();

app.use(express.json());
app.use('/auth', authRoutes);
app.use('/files', requireAuth, fileRoutes);
app.use('/internal/dashboard', requireAuth, dashboardRoutes);

app.use((err: any, _req: express.Request, res: express.Response, _next: express.NextFunction) => {
  logger.error({ err }, 'Unhandled error');
  res.status(500).json({ error: 'Internal error' });
});

const port = Number(process.env.PORT || 4000);
app.listen(port, () => {
  logger.info(`CRM server listening on ${port}`);
});
