import express from 'express';
import authRoutes from './routes/auth.js';
import fileRoutes from './routes/files.js';
import dashboardRoutes from './routes/dashboard.js';
import usersRoutes from './routes/users.js';
import { requireAuth, requirePerm } from './middleware/auth.js';
import { rateLimit } from './middleware/rateLimit.js';
import { errorHandler } from './middleware/error.js';
import pino from 'pino';

const app = express();
const logger = pino();

app.use(express.json());
app.use('/auth', authRoutes);
app.use('/files', requireAuth, rateLimit(10, 60000), fileRoutes);
app.use('/internal/dashboard', requireAuth, requirePerm('dashboard.read'), dashboardRoutes);
app.use('/internal/users', requireAuth, usersRoutes);

app.use(errorHandler);

const port = Number(process.env.PORT || 4000);
app.listen(port, () => {
  logger.info(`CRM server listening on ${port}`);
});
