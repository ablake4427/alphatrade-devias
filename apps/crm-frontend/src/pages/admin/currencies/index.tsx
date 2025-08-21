import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { Switch, TextField, Button } from '@mui/material';
import { listCurrencies, updateCurrencyStatus, addCurrencyComment } from '../../../api/currencies';

export default function AdminCurrencies() {
  const { data } = useQuery({ queryKey: ['currencies'], queryFn: listCurrencies });
  const qc = useQueryClient();
  const [inputs, setInputs] = useState<Record<string, string>>({});

  const statusMutation = useMutation({
    mutationFn: ({ id, enabled, comment }: { id: string; enabled: boolean; comment: string }) =>
      updateCurrencyStatus(id, enabled, comment),
    onSuccess: () => qc.invalidateQueries({ queryKey: ['currencies'] }),
  });

  const commentMutation = useMutation({
    mutationFn: ({ id, comment }: { id: string; comment: string }) => addCurrencyComment(id, comment),
    onSuccess: () => qc.invalidateQueries({ queryKey: ['currencies'] }),
  });

  const handleToggle = (id: string, enabled: boolean) => {
    const comment = window.prompt('Comment for status change?') || '';
    statusMutation.mutate({ id, enabled, comment });
  };

  return (
    <div>
      <h1>Currencies</h1>
      {data?.data?.map((c) => (
        <div key={c.id} style={{ border: '1px solid #ccc', margin: '1rem 0', padding: '0.5rem' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '1rem' }}>
            <strong>{c.code}</strong>
            <Switch checked={c.enabled} onChange={() => handleToggle(c.id, !c.enabled)} />
          </div>
          <ul>
            {c.comments?.map((cm) => (
              <li key={cm.id}>{cm.comment}</li>
            ))}
          </ul>
          <div style={{ display: 'flex', gap: '0.5rem' }}>
            <TextField
              size="small"
              value={inputs[c.id] || ''}
              onChange={(e) => setInputs({ ...inputs, [c.id]: e.target.value })}
            />
            <Button
              variant="contained"
              onClick={() => {
                const text = inputs[c.id];
                if (!text) return;
                commentMutation.mutate({ id: c.id, comment: text });
                setInputs({ ...inputs, [c.id]: '' });
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
