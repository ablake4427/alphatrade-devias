import { useState } from 'react';
import { useMutation, useQuery } from '@tanstack/react-query';
import CommentPanel from '../../components/admin/comment-panel';
import * as coinPairs from '../../api/coinPairs';

export default function CoinPairs() {
  const [form, setForm] = useState({ pair: '', status: 'active' });
  const { data, refetch } = useQuery({ queryKey: ['coin-pairs'], queryFn: coinPairs.list });
  const create = useMutation({
    mutationFn: () => coinPairs.create(form),
    onSuccess: () => {
      setForm({ pair: '', status: 'active' });
      refetch();
    },
  });
  return (
    <div>
      <h1>Coin Pairs</h1>
      <form
        onSubmit={(e) => {
          e.preventDefault();
          create.mutate();
        }}
      >
        <input
          value={form.pair}
          onChange={(e) => setForm({ ...form, pair: e.target.value })}
          placeholder="BTC/USDT"
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
      <CommentPanel resource="coin-pairs" />
    </div>
  );
}
