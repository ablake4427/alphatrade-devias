import { useState } from 'react';
import { useMutation, useQuery } from '@tanstack/react-query';
import CommentPanel from '../../components/admin/comment-panel';
import * as referrals from '../../api/referrals';

export default function Referrals() {
  const [form, setForm] = useState({ code: '', status: 'active' });
  const { data, refetch } = useQuery({ queryKey: ['referrals'], queryFn: referrals.list });
  const create = useMutation({
    mutationFn: () => referrals.create(form),
    onSuccess: () => {
      setForm({ code: '', status: 'active' });
      refetch();
    },
  });
  return (
    <div>
      <h1>Referrals</h1>
      <form
        onSubmit={(e) => {
          e.preventDefault();
          create.mutate();
        }}
      >
        <input
          value={form.code}
          onChange={(e) => setForm({ ...form, code: e.target.value })}
          placeholder="Code"
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
      <CommentPanel resource="referrals" />
    </div>
  );
}
