export const listWithdrawals = `
  SELECT * FROM withdrawals
  WHERE (? IS NULL OR status = ?)
    AND (? IS NULL OR user_id = ?)
    AND created_at BETWEEN ? AND ?
  ORDER BY id DESC
  LIMIT ? OFFSET ?
`;

export const approveWithdrawal = `
  UPDATE withdrawals SET status='approved', approved_at=NOW()
  WHERE id=? AND status='pending'
`;

export const rejectWithdrawal = `
  UPDATE withdrawals SET status='rejected', rejected_reason=?, rejected_at=NOW()
  WHERE id=? AND status='pending'
`;
