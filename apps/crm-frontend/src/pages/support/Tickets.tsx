import { useQuery } from '@tanstack/react-query';
import { apiFetch } from '../../api/client';

export default function Tickets() {
  const { data } = useQuery({ queryKey: ['support', 'tickets'], queryFn: () => apiFetch('/internal/support/tickets') });
  return (
    <div>
      <h1>Support Tickets</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
    </div>
  );
}
