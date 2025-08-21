import { apiFetch } from './client';

export interface BinaryTrade {
  id: string;
  status: string;
}

export interface TradeComment {
  id: string;
  message: string;
}

export async function listBinaryTrades(status: string): Promise<BinaryTrade[]> {
  const res = await apiFetch(`/internal/binary-trades?status=${status}`);
  return res.data || [];
}

export async function getTradeComments(id: string): Promise<TradeComment[]> {
  const res = await apiFetch(`/internal/binary-trades/${id}/comments`);
  return res.data || [];
}

export async function addTradeComment(id: string, message: string) {
  return apiFetch(`/internal/binary-trades/${id}/comments`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ message })
  });
}
