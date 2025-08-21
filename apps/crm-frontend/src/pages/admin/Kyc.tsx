import { useState } from 'react';
import { useMutation, useQuery } from '@tanstack/react-query';
import CommentPanel from '../../components/admin/comment-panel';
import * as kyc from '../../api/kyc';

export default function Kyc() {
  const [form, setForm] = useState({ userId: '', status: 'pending' });
  const { data, refetch } = useQuery({ queryKey: ['kyc'], queryFn: kyc.list });
  const create = useMutation({
    mutationFn: () => kyc.create(form),
    onSuccess: () => {
      setForm({ userId: '', status: 'pending' });
      refetch();
    },
  });
  return (
    <div>
      <h1>KYC</h1>
      <form
        onSubmit={(e) => {
          e.preventDefault();
          create.mutate();
        }}
      >
        <input
          value={form.userId}
          onChange={(e) => setForm({ ...form, userId: e.target.value })}
          placeholder="User ID"
        />
        <select
          value={form.status}
          onChange={(e) => setForm({ ...form, status: e.target.value })}
        >
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
        <button type="submit">Add</button>
      </form>
      <pre>{JSON.stringify(data, null, 2)}</pre>
      <CommentPanel resource="kyc" />
    </div>
  );
}
