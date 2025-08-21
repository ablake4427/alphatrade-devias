import { useState } from 'react';
import { useQuery, useMutation } from '@tanstack/react-query';
import { useParams } from 'react-router-dom';
import { apiFetch } from '../../api/client';

export default function Ticket() {
  const { id } = useParams();
  const { data, refetch } = useQuery({
    queryKey: ['support', 'ticket', id],
    queryFn: () => apiFetch(`/internal/support/tickets/${id}`),
    enabled: !!id,
  });
  const [message, setMessage] = useState('');
  const mutation = useMutation({
    mutationFn: () =>
      apiFetch(`/internal/support/tickets/${id}/reply`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message }),
      }),
    onSuccess: () => {
      setMessage('');
      refetch();
    },
  });
  return (
    <div>
      <h1>Ticket {id}</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
      <form
        onSubmit={(e) => {
          e.preventDefault();
          mutation.mutate();
        }}
      >
        <textarea value={message} onChange={(e) => setMessage(e.target.value)} />
        <button type="submit">Reply</button>
      </form>
    </div>
  );
}
