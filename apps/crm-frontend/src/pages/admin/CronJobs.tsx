import { useState } from 'react';
import { useMutation, useQuery } from '@tanstack/react-query';
import CommentPanel from '../../components/admin/comment-panel';
import * as cronJobs from '../../api/cronJobs';

export default function CronJobs() {
  const [form, setForm] = useState({ name: '', schedule: '', status: 'active' });
  const { data, refetch } = useQuery({ queryKey: ['cron-jobs'], queryFn: cronJobs.list });
  const create = useMutation({
    mutationFn: () => cronJobs.create(form),
    onSuccess: () => {
      setForm({ name: '', schedule: '', status: 'active' });
      refetch();
    },
  });
  return (
    <div>
      <h1>Cron Jobs</h1>
      <form
        onSubmit={(e) => {
          e.preventDefault();
          create.mutate();
        }}
      >
        <input
          value={form.name}
          onChange={(e) => setForm({ ...form, name: e.target.value })}
          placeholder="Name"
        />
        <input
          value={form.schedule}
          onChange={(e) => setForm({ ...form, schedule: e.target.value })}
          placeholder="* * * * *"
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
      <CommentPanel resource="cron-jobs" />
    </div>
  );
}
