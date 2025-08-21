import { useState } from 'react';
import { z } from 'zod';

const schema = z.object({
  username: z.string(),
  password: z.string()
});

export default function SignIn({ onToken }: { onToken: (token: string) => void }) {
  const [form, setForm] = useState({ username: '', password: '' });
  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const parsed = schema.safeParse(form);
    if (!parsed.success) return;
    const res = await fetch('/auth/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(parsed.data)
    });
    if (res.ok) {
      const data = await res.json();
      onToken(data.token);
    }
  };
  return (
    <form onSubmit={handleSubmit}>
      <input value={form.username} onChange={(e) => setForm({ ...form, username: e.target.value })} placeholder="email" />
      <input type="password" value={form.password} onChange={(e) => setForm({ ...form, password: e.target.value })} placeholder="password" />
      <button type="submit">Login</button>
    </form>
  );
}
