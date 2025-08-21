import { apiFetch } from './client';

export interface BinaryTrade {
  id: string;
  status: string;
}

export async function listBinaryTrades(status: string): Promise<BinaryTrade[]> {
  const res = await apiFetch(`/internal/binary/trades?status=${status}`);
  return res.data || [];
}

export async function refundBinaryTrade(id: string) {
  return apiFetch(`/internal/binary/trades/${id}/refund`, { method: 'POST' });
}
