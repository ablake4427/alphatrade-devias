import { useQuery } from '@tanstack/react-query';
import { apiFetch } from '../api/client';

export default function ReportAgentPerformance() {
  const { data } = useQuery({
    queryKey: ['report-agent-performance'],
    queryFn: () => apiFetch('/internal/reports/agent-performance')
  });
  return (
    <div>
      <h1>Agent Performance Report</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
    </div>
  );
}
