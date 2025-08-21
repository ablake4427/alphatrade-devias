export const listDeposits = `
  SELECT * FROM deposits
  WHERE (? IS NULL OR status = ?)
    AND (? IS NULL OR user_id = ?)
    AND created_at BETWEEN ? AND ?
  ORDER BY id DESC
  LIMIT ? OFFSET ?
`;

export const approveDeposit = `
  UPDATE deposits SET status='approved', approved_at=NOW()
  WHERE id=? AND status='pending'
`;

export const rejectDeposit = `
  UPDATE deposits SET status='rejected', rejected_reason=?, rejected_at=NOW()
  WHERE id=? AND status='pending'
`;
