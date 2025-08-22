import { useQuery } from '@tanstack/react-query';
import { apiFetch } from '../../api/client';

export default function Opportunities() {
  const { data } = useQuery({ queryKey: ['crm','opportunities'], queryFn: () => apiFetch('/internal/crm/opportunities') });
  return (
    <div>
      <h1>CRM Opportunities</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
    </div>
  );
}
