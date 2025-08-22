import { useQuery } from '@tanstack/react-query';
import { apiFetch } from '../api/client';

export default function Trades() {
  const { data } = useQuery({ queryKey: ['spotTrades'], queryFn: () => apiFetch('/internal/spot/trades') });
  return (
    <div>
      <h1>Spot Trades</h1>
      <pre>{JSON.stringify(data?.data, null, 2)}</pre>
    </div>
  );
}
