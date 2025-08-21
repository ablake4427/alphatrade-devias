import { apiFetch } from './client';

export interface MarketComment {
  id: string;
  comment: string;
  createdAt: string;
}

export interface Market {
  id: string;
  symbol: string;
  enabled: boolean;
  comments?: MarketComment[];
}

export async function listMarkets(): Promise<{ data: Market[] }> {
  return apiFetch('/internal/markets');
}

export async function updateMarketStatus(id: string, enabled: boolean, comment: string) {
  return apiFetch(`/internal/markets/${id}/status`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ enabled, comment }),
  });
}

export async function addMarketComment(id: string, comment: string) {
  return apiFetch(`/internal/markets/${id}/comments`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ comment }),
  });
}
