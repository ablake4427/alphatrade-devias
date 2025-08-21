import { useState } from 'react';
import { useMutation, useQuery } from '@tanstack/react-query';
import CommentPanel from '../../components/admin/comment-panel';
import * as systemInfo from '../../api/systemInfo';

export default function SystemInfo() {
  const [form, setForm] = useState({ key: '', value: '', status: 'ok' });
  const { data, refetch } = useQuery({ queryKey: ['system-info'], queryFn: systemInfo.list });
  const create = useMutation({
    mutationFn: () => systemInfo.create(form),
    onSuccess: () => {
      setForm({ key: '', value: '', status: 'ok' });
      refetch();
    },
  });
  return (
    <div>
      <h1>System Info</h1>
      <form
        onSubmit={(e) => {
          e.preventDefault();
          create.mutate();
        }}
      >
        <input
          value={form.key}
          onChange={(e) => setForm({ ...form, key: e.target.value })}
          placeholder="Key"
        />
        <input
          value={form.value}
          onChange={(e) => setForm({ ...form, value: e.target.value })}
          placeholder="Value"
        />
        <select
          value={form.status}
          onChange={(e) => setForm({ ...form, status: e.target.value })}
        >
          <option value="ok">OK</option>
          <option value="warning">Warning</option>
          <option value="error">Error</option>
        </select>
        <button type="submit">Add</button>
      </form>
      <pre>{JSON.stringify(data, null, 2)}</pre>
      <CommentPanel resource="system-info" />
    </div>
  );
}
