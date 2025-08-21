import { useQuery } from '@tanstack/react-query';
import { apiFetch } from '../api/client';

export default function Users() {
  const { data } = useQuery({ queryKey: ['users'], queryFn: () => apiFetch('/internal/users') });
  return (
    <div>
      <h1>Users</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
    </div>
  );
}
