import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { Switch, TextField, Button } from '@mui/material';
import { listMarkets, updateMarketStatus, addMarketComment } from '../../../api/markets';

export default function AdminMarkets() {
  const { data } = useQuery({ queryKey: ['markets'], queryFn: listMarkets });
  const qc = useQueryClient();
  const [inputs, setInputs] = useState<Record<string, string>>({});

  const statusMutation = useMutation({
    mutationFn: ({ id, enabled, comment }: { id: string; enabled: boolean; comment: string }) =>
      updateMarketStatus(id, enabled, comment),
    onSuccess: () => qc.invalidateQueries({ queryKey: ['markets'] }),
  });

  const commentMutation = useMutation({
    mutationFn: ({ id, comment }: { id: string; comment: string }) => addMarketComment(id, comment),
    onSuccess: () => qc.invalidateQueries({ queryKey: ['markets'] }),
  });

  const handleToggle = (id: string, enabled: boolean) => {
    const comment = window.prompt('Comment for status change?') || '';
    statusMutation.mutate({ id, enabled, comment });
  };

  return (
    <div>
      <h1>Markets</h1>
      {data?.data?.map((m) => (
        <div key={m.id} style={{ border: '1px solid #ccc', margin: '1rem 0', padding: '0.5rem' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '1rem' }}>
            <strong>{m.symbol}</strong>
            <Switch checked={m.enabled} onChange={() => handleToggle(m.id, !m.enabled)} />
          </div>
          <ul>
            {m.comments?.map((cm) => (
              <li key={cm.id}>{cm.comment}</li>
            ))}
          </ul>
          <div style={{ display: 'flex', gap: '0.5rem' }}>
            <TextField
              size="small"
              value={inputs[m.id] || ''}
              onChange={(e) => setInputs({ ...inputs, [m.id]: e.target.value })}
            />
            <Button
              variant="contained"
              onClick={() => {
                const text = inputs[m.id];
                if (!text) return;
                commentMutation.mutate({ id: m.id, comment: text });
                setInputs({ ...inputs, [m.id]: '' });
              }}
            >
              Add Comment
            </Button>
          </div>
        </div>
      ))}
    </div>
  );
}
