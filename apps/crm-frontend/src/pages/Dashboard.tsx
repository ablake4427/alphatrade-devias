import { useQuery } from '@tanstack/react-query';

export default function Dashboard() {
  const { data } = useQuery({ queryKey: ['summary'], queryFn: async () => {
    const res = await fetch('/internal/dashboard/summary', { headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` } });
    if (!res.ok) throw new Error('failed');
    return res.json();
  }});
  return (
    <div>
      <h1>Dashboard</h1>

      <a href="/users">Users</a>


      <pre>{JSON.stringify(data, null, 2)}</pre>
    </div>
  );
}
