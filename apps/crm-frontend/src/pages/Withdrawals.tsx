import { useEffect, useState } from 'react';
import { apiFetch } from '../api/client';

export default function Withdrawals() {
  const [rows, setRows] = useState<any[]>([]);
  useEffect(() => {
    apiFetch('/internal/withdrawals').then(res => setRows(res.data || []));
  }, []);
  return (
    <div>
      <h1>Withdrawals</h1>
      <pre>{JSON.stringify(rows, null, 2)}</pre>
    </div>
  );
}
