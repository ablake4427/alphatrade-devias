import { useEffect, useState } from 'react';
import { listComments, addComment, updateComment, deleteComment, Comment } from '../../api/comments';

interface CommentThreadProps {
  entityId: string;
}

export default function CommentThread({ entityId }: CommentThreadProps) {
  const [comments, setComments] = useState<Comment[]>([]);
  const [newText, setNewText] = useState('');
  const [editingId, setEditingId] = useState<string | null>(null);
  const [editingText, setEditingText] = useState('');

  useEffect(() => {
    listComments(entityId).then(setComments).catch(console.error);
  }, [entityId]);

  const handleAdd = async () => {
    if (!newText.trim()) return;
    try {
      const comment = await addComment(entityId, newText);
      setComments((prev) => [...prev, comment]);
      setNewText('');
    } catch (err) {
      console.error(err);
    }
  };

  const handleDelete = async (id: string) => {
    try {
      await deleteComment(entityId, id);
      setComments((prev) => prev.filter((c) => c.id !== id));
    } catch (err) {
      console.error(err);
    }
  };

  const handleEdit = async (id: string) => {
    try {
      const updated = await updateComment(entityId, id, editingText);
      setComments((prev) => prev.map((c) => (c.id === id ? updated : c)));
      setEditingId(null);
      setEditingText('');
    } catch (err) {
      console.error(err);
    }
  };

  return (
    <div>
      <h3>Comments</h3>
      <ul>
        {comments.map((c) => (
          <li key={c.id}>
            {editingId === c.id ? (
              <>
                <input value={editingText} onChange={(e) => setEditingText(e.target.value)} />
                <button onClick={() => handleEdit(c.id)}>Save</button>
                <button onClick={() => { setEditingId(null); setEditingText(''); }}>Cancel</button>
              </>
            ) : (
              <>
                <span>{c.content}</span>
                <button onClick={() => { setEditingId(c.id); setEditingText(c.content); }}>Edit</button>
                <button onClick={() => handleDelete(c.id)}>Delete</button>
              </>
            )}
          </li>
        ))}
      </ul>
      <div>
        <input value={newText} onChange={(e) => setNewText(e.target.value)} placeholder="Add comment" />
        <button onClick={handleAdd}>Add</button>
      </div>
    </div>
  );
}
