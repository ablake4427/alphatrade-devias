import { useQuery } from '@tanstack/react-query';
import { apiFetch } from '../../api/client';

export default function Chat() {
  const { data } = useQuery({ queryKey: ['crm','chat'], queryFn: () => apiFetch('/internal/crm/chat') });
  return (
    <div>
      <h1>CRM Staff Chat</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
    </div>
  );
}
