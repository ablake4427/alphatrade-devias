import { useQuery } from '@tanstack/react-query';
import { apiFetch } from '../../api/client';

export default function Leads() {
  const { data } = useQuery({ queryKey: ['crm','leads'], queryFn: () => apiFetch('/internal/crm/leads') });
  return (
    <div>
      <h1>CRM Leads</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
    </div>
  );
}
