export const createCommentsTable = `
  CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entity_id VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  )`;

export const listComments = `
  SELECT id, entity_id, content, created_at
  FROM comments
  WHERE entity_id = ?
  ORDER BY id ASC
`;

export const getComment = `
  SELECT id, entity_id, content, created_at
  FROM comments
  WHERE id = ?
`;

export const insertComment = `
  INSERT INTO comments (entity_id, content)
  VALUES (?, ?)
`;

export const updateComment = `
  UPDATE comments
  SET content = ?
  WHERE id = ? AND entity_id = ?
`;

export const deleteComment = `
  DELETE FROM comments
  WHERE id = ? AND entity_id = ?
`;
