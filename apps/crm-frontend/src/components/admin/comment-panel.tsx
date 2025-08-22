import { useState } from 'react';
import { useMutation, useQuery } from '@tanstack/react-query';
import { listComments, addComment, Comment } from '../../api/comments';

export default function CommentPanel({ resource }: { resource: string }) {
  const [text, setText] = useState('');
  const { data: comments = [], refetch } = useQuery<Comment[]>({
    queryKey: [resource, 'comments'],
    queryFn: () => listComments(resource),
  });

  const mutation = useMutation({
    mutationFn: async () => addComment(resource, text),
    onSuccess: () => {
      setText('');
      refetch();
    },
  });

  return (
    <div>
      <h3>Comments</h3>
      <ul>
        {comments.map((c) => (
          <li key={c.id}>{c.content}</li>
        ))}
      </ul>
      <form
        onSubmit={(e) => {
          e.preventDefault();
          if (text.trim()) mutation.mutate();
        }}
      >
        <input
          value={text}
          onChange={(e) => setText(e.target.value)}
          placeholder="Add comment"
        />
        <button type="submit">Add</button>
      </form>
    </div>
  );
}
