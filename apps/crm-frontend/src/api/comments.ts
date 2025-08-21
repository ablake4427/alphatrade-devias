import { apiFetch } from './client';

export interface Comment {
  id: string;
  content: string;
}

export function listComments(entityId: string): Promise<Comment[]> {
  return apiFetch(`/api/comments?entityId=${encodeURIComponent(entityId)}`);
}

export function addComment(entityId: string, content: string): Promise<Comment> {
  return apiFetch(`/api/comments?entityId=${encodeURIComponent(entityId)}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ content }),
  });
}

export function updateComment(entityId: string, id: string, content: string): Promise<Comment> {
  return apiFetch(`/api/comments/${id}?entityId=${encodeURIComponent(entityId)}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ content }),
  });
}

export function deleteComment(entityId: string, id: string): Promise<void> {
  return apiFetch(`/api/comments/${id}?entityId=${encodeURIComponent(entityId)}`, {
    method: 'DELETE',
  });
}
