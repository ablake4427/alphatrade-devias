import { apiFetch } from './client';

export interface PaymentMethod {
  id: string;
  name: string;
  active: boolean;
}

export async function listPaymentMethods(): Promise<PaymentMethod[]> {
  const res = await apiFetch('/internal/p2p/payment-methods');
  return res.data || [];
}

export async function togglePaymentMethod(id: string, active: boolean) {
  return apiFetch(`/internal/p2p/payment-methods/${id}`, {
    method: 'PATCH',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ active })
  });
}

export interface P2PAd {
  id: number;
  type: number;
  user_id: number;
  asset_id: number;
  fiat_id: number;
  price: string;
  minimum_amount: string;
  maximum_amount: string;
  status: number;
}

export async function listAds(): Promise<P2PAd[]> {
  const res = await apiFetch('/internal/p2p/ads');
  return res.data || [];
}

export async function createAd(data: Partial<P2PAd>) {
  return apiFetch('/internal/p2p/ads', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
}

export async function updateAd(id: number, data: Partial<P2PAd>) {
  return apiFetch(`/internal/p2p/ads/${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
}

export async function toggleAd(id: number) {
  return apiFetch(`/internal/p2p/ads/${id}/toggle`, { method: 'POST' });
}

export interface P2PTrade {
  id: number;
  uid: string;
  type: number;
  ad_id: number;
  buyer_id: number;
  seller_id: number;
  asset_amount: string;
  fiat_amount: string;
  price: string;
  status: number;
}

export async function listTrades(): Promise<P2PTrade[]> {
  const res = await apiFetch('/internal/p2p/trades');
  return res.data || [];
}

export async function getTrade(id: number) {
  return apiFetch(`/internal/p2p/trades/${id}`);
}

export async function completeTrade(id: number) {
  return apiFetch(`/internal/p2p/trades/${id}/complete`, { method: 'POST' });
}

export async function postTradeMessage(id: number, message: string) {
  return apiFetch(`/internal/p2p/trades/${id}/message`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ message })
  });
}

export async function resolveDispute(id: number) {
  return apiFetch(`/internal/p2p/disputes/${id}/resolve`, { method: 'POST' });
}
