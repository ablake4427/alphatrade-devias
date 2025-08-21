import { useQuery } from '@tanstack/react-query';
import { apiFetch } from '../api/client';

export default function ReportNotifications() {
  const { data } = useQuery({
    queryKey: ['report-notifications'],
    queryFn: () => apiFetch('/internal/reports/notifications')
  });
  return (
    <div>
      <h1>Notifications Report</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
    </div>
  );
}
