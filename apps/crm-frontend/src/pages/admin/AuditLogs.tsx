import { useQuery } from '@tanstack/react-query';
import CommentPanel from '../../components/admin/comment-panel';
import * as audit from '../../api/audit';

export default function AuditLogs() {
  const { data } = useQuery({ queryKey: ['audit-logs'], queryFn: () => audit.list() });
  return (
    <div>
      <h1>Audit Logs</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
      <CommentPanel resource="audit-logs" />
    </div>
  );
}
