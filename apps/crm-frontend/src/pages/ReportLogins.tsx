import { useQuery } from '@tanstack/react-query';
import { apiFetch } from '../api/client';

export default function ReportLogins() {
  const { data } = useQuery({
    queryKey: ['report-logins'],
    queryFn: () => apiFetch('/internal/reports/logins')
  });
  return (
    <div>
      <h1>Logins Report</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
    </div>
  );
}
