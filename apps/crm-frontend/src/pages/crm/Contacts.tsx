import { useQuery } from '@tanstack/react-query';
import { apiFetch } from '../../api/client';

export default function Contacts() {
  const { data } = useQuery({ queryKey: ['crm','contacts'], queryFn: () => apiFetch('/internal/crm/contacts') });
  return (
    <div>
      <h1>CRM Contacts</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
    </div>
  );
}
