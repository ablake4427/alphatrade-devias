import express from 'express';
import pino from 'pino';

import authRoutes from './routes/auth.js';
import fileRoutes from './routes/files.js';
import dashboardRoutes from './routes/dashboard.js';
import usersRoutes from './routes/users.js';
import walletsRoutes from './routes/wallets.js';
import depositsRoutes from './routes/deposits.js';
import withdrawalsRoutes from './routes/withdrawals.js';
import supportRoutes from './routes/support.js';
import internalRoutes from './routes/internal.js';

import { requireAuth, requirePerm } from './middleware/auth.js';
import { rateLimit } from './middleware/rateLimit.js';
import { errorHandler } from './middleware/error.js';

const app = express();
const logger = pino();

app.use(express.json());
app.use('/auth', authRoutes);

app.use('/files', requireAuth, rateLimit(10, 60000), fileRoutes);
app.use('/internal/dashboard', requireAuth, requirePerm('dashboard.read'), dashboardRoutes);
app.use('/internal/users', requireAuth, usersRoutes);
app.use('/internal/wallets', requireAuth, walletsRoutes);
app.use('/internal/deposits', requireAuth, depositsRoutes);
app.use('/internal/withdrawals', requireAuth, withdrawalsRoutes);
app.use('/internal/support', requireAuth, supportRoutes);
app.use('/internal', requireAuth, internalRoutes);

app.use(errorHandler);

app.use((err: any, _req: express.Request, res: express.Response, _next: express.NextFunction) => {
  logger.error({ err }, 'Unhandled error');
  res.status(500).json({ error: 'Internal error' });
});

const port = Number(process.env.PORT || 4000);
app.listen(port, () => {
  logger.info(`CRM server listening on ${port}`);
});
