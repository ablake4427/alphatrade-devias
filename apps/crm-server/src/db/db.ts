import mysql from 'mysql2/promise';
import pino from 'pino';

const logger = pino({ level: 'info' });

export const pool = mysql.createPool({
  host: process.env.DB_HOST,
  port: Number(process.env.DB_PORT || 3306),
  database: process.env.DB_NAME,
  user: process.env.DB_USER,
  password: process.env.DB_PASS,
  waitForConnections: true,
  connectionLimit: 10,
  timezone: 'Z'
});

export async function query<T = any>(sql: string, params: any[] = []): Promise<T[]> {
  const [rows] = await pool.query(sql, params);
  return rows as T[];
}

export async function tx<T>(fn: (conn: mysql.Connection) => Promise<T>): Promise<T> {
  const conn = await pool.getConnection();
  try {
    await conn.beginTransaction();
    const result = await fn(conn);
    await conn.commit();
    return result;
  } catch (err) {
    await conn.rollback();
    throw err;
  } finally {
    conn.release();
  }
}

export async function assertTableExists(table: string): Promise<boolean> {
  try {
    const rows = await query<{ TABLE_NAME: string }>(
      'SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? LIMIT 1',
      [process.env.DB_NAME, table]
    );
    return rows.length > 0;
  } catch (err) {
    logger.warn({ err }, 'assertTableExists failed');
    return false;
  }
}
