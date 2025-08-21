import { useQuery } from '@tanstack/react-query';
import CommentPanel from '../../components/admin/comment-panel';
import * as alerts from '../../api/alerts';

export default function Alerts() {
  const { data } = useQuery({ queryKey: ['alerts'], queryFn: () => alerts.list() });
  return (
    <div>
      <h1>Alerts</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
      <CommentPanel resource="alerts" />
    </div>
  );
}
