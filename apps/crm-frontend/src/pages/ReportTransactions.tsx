import { useQuery } from '@tanstack/react-query';
import { apiFetch } from '../api/client';

export default function ReportTransactions() {
  const { data } = useQuery({
    queryKey: ['report-transactions'],
    queryFn: () => apiFetch('/internal/reports/transactions')
  });
  return (
    <div>
      <h1>Transactions Report</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
    </div>
  );
}
