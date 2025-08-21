import { useQuery } from '@tanstack/react-query';
import { apiFetch } from '../api/client';

export default function Orders() {
  const { data, refetch } = useQuery({ queryKey: ['spotOrders'], queryFn: () => apiFetch('/internal/spot/orders') });
  const cancel = async (id: number) => {
    await apiFetch(`/internal/spot/orders/${id}`, { method: 'DELETE' });
    refetch();
  };
  return (
    <div>
      <h1>Spot Orders</h1>
      {data?.data?.map((o: any) => (
        <div key={o.id} style={{ border: '1px solid #ccc', padding: 8, marginBottom: 8 }}>
          <pre>{JSON.stringify(o, null, 2)}</pre>
          <button onClick={() => cancel(o.id)}>Cancel</button>
        </div>
      ))}
    </div>
  );
}
