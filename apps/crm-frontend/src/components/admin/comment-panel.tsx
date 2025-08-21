import { useState } from 'react';
import { useMutation, useQuery } from '@tanstack/react-query';
import { apiFetch } from '../../api/client';

interface Comment {
  id?: string;
  text: string;
  createdAt?: string;
}

export default function CommentPanel({ resource }: { resource: string }) {
  const [text, setText] = useState('');
  const { data, refetch } = useQuery<{ data: Comment[] }>({
    queryKey: [resource, 'comments'],
    queryFn: () => apiFetch(`/internal/${resource}/comments`),
  });

  const mutation = useMutation({
    mutationFn: async () => {
      await apiFetch(`/internal/${resource}/comments`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ text }),
      });
    },
    onSuccess: () => {
      setText('');
      refetch();
    },
  });

  return (
    <div>
      <h3>Comments</h3>
      <ul>
        {(data?.data || []).map((c, i) => (
          <li key={c.id || i}>{c.text}</li>
        ))}
      </ul>
      <form
        onSubmit={(e) => {
          e.preventDefault();
          if (text.trim()) mutation.mutate();
        }}
      >
        <input
          value={text}
          onChange={(e) => setText(e.target.value)}
          placeholder="Add comment"
        />
        <button type="submit">Add</button>
      </form>
    </div>
  );
}
