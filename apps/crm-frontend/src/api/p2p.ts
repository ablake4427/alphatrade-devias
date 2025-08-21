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
