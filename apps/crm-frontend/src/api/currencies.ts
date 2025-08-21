import { apiFetch } from './client';

export interface CurrencyComment {
  id: string;
  comment: string;
  createdAt: string;
}

export interface Currency {
  id: string;
  code: string;
  enabled: boolean;
  comments?: CurrencyComment[];
}

export async function listCurrencies(): Promise<{ data: Currency[] }> {
  return apiFetch('/internal/currencies');
}

export async function updateCurrencyStatus(id: string, enabled: boolean, comment: string) {
  return apiFetch(`/internal/currencies/${id}/status`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ enabled, comment }),
  });
}

export async function addCurrencyComment(id: string, comment: string) {
  return apiFetch(`/internal/currencies/${id}/comments`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ comment }),
  });
}
