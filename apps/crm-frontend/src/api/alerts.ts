import { apiFetch } from './client';

const base = '/internal/alerts';

export const list = (params: any = {}) => {
  const query = new URLSearchParams(params).toString();
  const path = query ? `${base}?${query}` : base;
  return apiFetch(path);
};
