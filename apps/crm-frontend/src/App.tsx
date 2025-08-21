import { Routes, Route, Navigate } from 'react-router-dom';
import { useState } from 'react';
import SignIn from './pages/SignIn';
import Dashboard from './pages/Dashboard';
import Users from './pages/Users';
import Deposits from './pages/Deposits';
import Withdrawals from './pages/Withdrawals';
import Orders from './pages/Orders';
import Trades from './pages/Trades';
import Notifications from './pages/Notifications';
import Settings from './pages/Settings';
import Placeholder from './pages/Placeholder';
import Referrals from './pages/admin/Referrals';
import CoinPairs from './pages/admin/CoinPairs';
import GeneralSettings from './pages/admin/GeneralSettings';
import CronJobs from './pages/admin/CronJobs';
import Kyc from './pages/admin/Kyc';
import NotificationTemplates from './pages/admin/NotificationTemplates';
import SystemInfo from './pages/admin/SystemInfo';
import AuditLogs from './pages/admin/AuditLogs';
import Alerts from './pages/admin/Alerts';

import BinaryTrades from './pages/admin/binary-trades';
import P2PAdmin from './pages/admin/p2p';

import AdminCurrencies from './pages/admin/currencies';
import AdminMarkets from './pages/admin/markets';


export default function App() {
  const [token, setToken] = useState<string | null>(localStorage.getItem('token'));
  return (
    <Routes>
      <Route
        path="/login"
        element={<SignIn onToken={(t) => { setToken(t); localStorage.setItem('token', t); }} />}
      />
      <Route path="/" element={token ? <Dashboard /> : <Navigate to="/login" />} />
      <Route path="/users" element={token ? <Users /> : <Navigate to="/login" />} />
      <Route path="/deposits" element={token ? <Deposits /> : <Navigate to="/login" />} />
      <Route path="/withdrawals" element={token ? <Withdrawals /> : <Navigate to="/login" />} />

      <Route path="/orders" element={token ? <Orders /> : <Navigate to="/login" />} />
      <Route path="/trades" element={token ? <Trades /> : <Navigate to="/login" />} />
      <Route path="/notifications" element={token ? <Notifications /> : <Navigate to="/login" />} />



      <Route path="/admin/referrals" element={token ? <Referrals /> : <Navigate to="/login" />} />
      <Route path="/admin/coin-pairs" element={token ? <CoinPairs /> : <Navigate to="/login" />} />
      <Route path="/admin/general-settings" element={token ? <GeneralSettings /> : <Navigate to="/login" />} />
      <Route path="/admin/cron-jobs" element={token ? <CronJobs /> : <Navigate to="/login" />} />
      <Route path="/admin/kyc" element={token ? <Kyc /> : <Navigate to="/login" />} />
      <Route path="/admin/notification-templates" element={token ? <NotificationTemplates /> : <Navigate to="/login" />} />
      <Route path="/admin/system-info" element={token ? <SystemInfo /> : <Navigate to="/login" />} />


      <Route path="/admin/currencies" element={token ? <AdminCurrencies /> : <Navigate to="/login" />} />
      <Route path="/admin/markets" element={token ? <AdminMarkets /> : <Navigate to="/login" />} />


      <Route path="/trading/spot" element={token ? <Placeholder title="Trading Spot" /> : <Navigate to="/login" />} />
      <Route path="/trading/futures" element={token ? <Placeholder title="Trading Futures" /> : <Navigate to="/login" />} />
      <Route path="/admin/binary-trades/*" element={token ? <BinaryTrades /> : <Navigate to="/login" />} />
      <Route path="/admin/p2p/*" element={token ? <P2PAdmin /> : <Navigate to="/login" />} />
      <Route path="/support" element={token ? <Placeholder title="Support" /> : <Navigate to="/login" />} />
      <Route path="/crm/leads" element={token ? <Placeholder title="CRM Leads" /> : <Navigate to="/login" />} />
      <Route path="/crm/contacts" element={token ? <Placeholder title="CRM Contacts" /> : <Navigate to="/login" />} />
      <Route path="/crm/opportunities" element={token ? <Placeholder title="CRM Opportunities" /> : <Navigate to="/login" />} />
      <Route path="/crm/tasks" element={token ? <Placeholder title="CRM Tasks" /> : <Navigate to="/login" />} />
      <Route path="/crm/notes" element={token ? <Placeholder title="CRM Notes" /> : <Navigate to="/login" />} />
      <Route path="/crm/chat" element={token ? <Placeholder title="CRM Staff Chat" /> : <Navigate to="/login" />} />
      <Route path="/reports" element={token ? <Placeholder title="Reports" /> : <Navigate to="/login" />} />

      <Route path="/settings" element={token ? <Settings /> : <Navigate to="/login" />} />
      <Route path="/system/cron" element={token ? <Placeholder title="System Cron" /> : <Navigate to="/login" />} />

      <Route path="/settings" element={token ? <GeneralSettings /> : <Navigate to="/login" />} />
      <Route path="/system/cron" element={token ? <CronJobs /> : <Navigate to="/login" />} />
      <Route path="/system/info" element={token ? <SystemInfo /> : <Navigate to="/login" />} />

      <Route path="/system/audit" element={token ? <AuditLogs /> : <Navigate to="/login" />} />
      <Route path="/system/alerts" element={token ? <Alerts /> : <Navigate to="/login" />} />
      <Route path="/*" element={token ? <Dashboard /> : <Navigate to="/login" />} />
    </Routes>
  );
}
