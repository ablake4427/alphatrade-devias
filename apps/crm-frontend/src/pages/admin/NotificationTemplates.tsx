import { useState } from 'react';
import { useMutation, useQuery } from '@tanstack/react-query';
import CommentPanel from '../../components/admin/comment-panel';
import * as notificationTemplates from '../../api/notificationTemplates';

export default function NotificationTemplates() {
  const [form, setForm] = useState({ name: '', content: '', status: 'active' });
  const { data, refetch } = useQuery({ queryKey: ['notification-templates'], queryFn: notificationTemplates.list });
  const create = useMutation({
    mutationFn: () => notificationTemplates.create(form),
    onSuccess: () => {
      setForm({ name: '', content: '', status: 'active' });
      refetch();
    },
  });
  return (
    <div>
      <h1>Notification Templates</h1>
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
          value={form.content}
          onChange={(e) => setForm({ ...form, content: e.target.value })}
          placeholder="Content"
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
      <CommentPanel resource="notification-templates" />
    </div>
  );
}
