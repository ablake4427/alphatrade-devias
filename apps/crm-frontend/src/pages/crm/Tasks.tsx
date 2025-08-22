import { useQuery } from '@tanstack/react-query';
import { apiFetch } from '../../api/client';

export default function Tasks() {
  const { data } = useQuery({ queryKey: ['crm','tasks'], queryFn: () => apiFetch('/internal/crm/tasks') });
  return (
    <div>
      <h1>CRM Tasks</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
    </div>
  );
}
