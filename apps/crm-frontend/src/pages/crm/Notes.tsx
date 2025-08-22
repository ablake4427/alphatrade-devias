import { useQuery } from '@tanstack/react-query';
import { apiFetch } from '../../api/client';

export default function Notes() {
  const { data } = useQuery({ queryKey: ['crm','notes'], queryFn: () => apiFetch('/internal/crm/notes') });
  return (
    <div>
      <h1>CRM Notes</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
    </div>
  );
}
