import { useState } from 'react';
import { useMutation, useQuery } from '@tanstack/react-query';
import CommentPanel from '../../components/admin/comment-panel';
import * as generalSettings from '../../api/generalSettings';

export default function GeneralSettings() {
  const [form, setForm] = useState({ key: '', value: '', status: 'active' });
  const { data, refetch } = useQuery({ queryKey: ['general-settings'], queryFn: generalSettings.list });
  const create = useMutation({
    mutationFn: () => generalSettings.create(form),
    onSuccess: () => {
      setForm({ key: '', value: '', status: 'active' });
      refetch();
    },
  });
  return (
    <div>
      <h1>General Settings</h1>
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
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
        <button type="submit">Add</button>
      </form>
      <pre>{JSON.stringify(data, null, 2)}</pre>
      <CommentPanel resource="general-settings" />
    </div>
  );
}
