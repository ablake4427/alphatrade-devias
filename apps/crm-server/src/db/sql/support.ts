export const createTicketsTable = `
  CREATE TABLE IF NOT EXISTS support_tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subject VARCHAR(255) NOT NULL,
    status ENUM('open','closed') DEFAULT 'open',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    closed_at DATETIME NULL
  )
`;

export const createMessagesTable = `
  CREATE TABLE IF NOT EXISTS support_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    sender ENUM('user','admin') NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
`;

export const listTickets = `
  SELECT id, user_id, subject, status, created_at, closed_at
  FROM support_tickets
  ORDER BY id DESC
  LIMIT ? OFFSET ?
`;

export const getTicket = `
  SELECT id, user_id, subject, status, created_at, closed_at
  FROM support_tickets
  WHERE id = ?
`;

export const getMessages = `
  SELECT id, ticket_id, sender, message, created_at
  FROM support_messages
  WHERE ticket_id = ?
  ORDER BY id ASC
`;

export const insertMessage = `
  INSERT INTO support_messages (ticket_id, sender, message)
  VALUES (?, ?, ?)
`;

export const closeTicket = `
  UPDATE support_tickets SET status='closed', closed_at=NOW()
  WHERE id = ?
`;
