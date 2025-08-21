import { Routes, Route, Navigate } from 'react-router-dom';
import { useState } from 'react';
import SignIn from './pages/SignIn';
import Dashboard from './pages/Dashboard';
import Users from './pages/Users';

export default function App() {
  const [token, setToken] = useState<string | null>(localStorage.getItem('token'));
  return (
    <Routes>
      <Route path="/login" element={<SignIn onToken={(t) => { setToken(t); localStorage.setItem('token', t); }} />} />
      <Route path="/" element={token ? <Dashboard /> : <Navigate to="/login" />} />
      <Route path="/users" element={token ? <Users /> : <Navigate to="/login" />} />
    </Routes>
  );
}
