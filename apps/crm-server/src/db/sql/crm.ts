export const listLeads = `SELECT * FROM crm_leads ORDER BY id DESC LIMIT ? OFFSET ?`;
export const insertLead = `INSERT INTO crm_leads (name, email, status) VALUES (?, ?, ?)`;
export const updateLead = `UPDATE crm_leads SET name = ?, email = ?, status = ? WHERE id = ?`;

export const listContacts = `SELECT * FROM crm_contacts ORDER BY id DESC LIMIT ? OFFSET ?`;
export const insertContact = `INSERT INTO crm_contacts (name, email, phone) VALUES (?, ?, ?)`;

export const listOpportunities = `SELECT * FROM crm_opportunities ORDER BY id DESC LIMIT ? OFFSET ?`;
export const insertOpportunity = `INSERT INTO crm_opportunities (name, value, stage) VALUES (?, ?, ?)`;
export const updateOpportunity = `UPDATE crm_opportunities SET name = ?, value = ?, stage = ? WHERE id = ?`;
export const updateOpportunityStage = `UPDATE crm_opportunities SET stage = ? WHERE id = ?`;

export const listTasks = `SELECT * FROM crm_tasks ORDER BY id DESC LIMIT ? OFFSET ?`;
export const insertTask = `INSERT INTO crm_tasks (title, due_date, status) VALUES (?, ?, ?)`;

export const listNotes = `SELECT * FROM crm_notes ORDER BY id DESC LIMIT ? OFFSET ?`;
export const insertNote = `INSERT INTO crm_notes (entity, entity_id, note) VALUES (?, ?, ?)`;

export const listChat = `SELECT * FROM crm_chat ORDER BY id DESC LIMIT ? OFFSET ?`;
export const insertChat = `INSERT INTO crm_chat (sender_id, message) VALUES (?, ?)`;
