import { apiFetch } from './client';

const base = '/internal/referrals';

export const list = () => apiFetch(base);
export const create = (data: any) =>
  apiFetch(base, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data),
  });
export const update = (id: string, data: any) =>
  apiFetch(`${base}/${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data),
  });
export const remove = (id: string) =>
  apiFetch(`${base}/${id}`, { method: 'DELETE' });
